<?php

namespace Bfg\Wood;

use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Nodes\ClassPropertyNode;
use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\EnumSubject;
use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Comcode\Subjects\TraitSubject;
use Bfg\Wood\Generators\DefaultGenerator;
use Bfg\Wood\Models\Php;
use Bfg\Wood\Models\PhpSubject;
use ErrorException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class ClassFactory
{
    /**
     * @var array|ClassSubject[]
     */
    protected array $classes = [];

    protected array $generatorInstances = [];

    protected array $syncGeneratorInstances = [];

    public function __construct()
    {
        $this->prepareComcode();
    }

    protected function prepareComcode(): void
    {
        ClassSubject::on('method', [$this, 'onMethodCreate']);
        ClassSubject::on('property', [$this, 'onPropertyCreate']);
    }

    public function onMethodCreate(
        ClassSubject $subject,
        ClassMethodNode $node,
    ): void {
        PhpSubject::createOrUpdateSubject(
            $subject, $node, 'method'
        );
    }

    public function onPropertyCreate(
        ClassSubject $subject,
        ClassPropertyNode $node,
    ): void {
        PhpSubject::createOrUpdateSubject(
            $subject, $node, 'property'
        );
    }

    /**
     * @template Subject
     * @param  string  $type
     * @param  string  $value
     * @param  ModelTopic|null  $modelTopic
     * @return Subject
     * @throws Exception
     */
    public function watchClass(string $type, string $value, ?ModelTopic $modelTopic): ClassSubject
    {
        try {
            $subject = \php()->{$type}($value);
        } catch (\Throwable $exception) {
            throw new Exception(
                "PARSE ERROR\n[$type]:[$value]\n" . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        if (isset($this->classes[$subject->fileSubject->file])) {

            return $this->classes[$subject->fileSubject->file];
        }

        if ($modelTopic) {

            $classType = $subject instanceof EnumSubject ? 'enum' : ($subject instanceof InterfaceSubject ? 'interface'
                : ($subject instanceof TraitSubject ? 'trait' : ($subject instanceof AnonymousClassSubject ? 'anonymous': 'class')));

            $php = Php::where('inode', fileinode($subject->fileSubject->file))->first();
            $php = $php ?: Php::findByTopic($modelTopic, $classType);

            if ($php) {
                $file = str_replace(base_path(), '', $subject->fileSubject->file);
                if ($file !== $php->file) {
                    if (is_file(base_path($php->file))) {
                        rename(
                            base_path($php->file),
                            base_path($file)
                        );
                    }
                    try {
                        $subject = \php()->{$type}($value);
                    } catch (\Throwable $exception) {
                        throw new Exception(
                            "PARSE ERROR\n[$type]:[$value]\n" . $exception->getMessage(),
                            $exception->getCode(),
                            $exception
                        );
                    }
                }
            }

            $subject->php = $php;

            $subject->modelTopic = $modelTopic;
        } else {
            Php::createOrUpdatePhp($subject);
        }

        return $this->classes[$subject->fileSubject->file]
            = $subject;
    }

    /**
     * @return $this
     * @throws BindingResolutionException
     */
    public function generate(): static
    {
        app()->make(DefaultGenerator::class);

        foreach (\Wood::getTopics() as $topic) {

            if ($generators = $topic::getGenerators()) {
                foreach ($generators as $generator) {
                    $this->generatorInstances[] = app()->make($generator);
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws BindingResolutionException
     */
    public function syncWithExistsCode(): static
    {
        app()->make(\Bfg\Wood\SyncGenerators\DefaultGenerator::class);

        foreach (\Wood::getTopics() as $topic) {

            if ($generators = $topic::getSyncGenerators()) {
                foreach ($generators as $generator) {
                    $this->syncGeneratorInstances[] = app()->make($generator);
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function save(callable $cb, callable $cbDel): static
    {
        $saved = [];

        foreach ($this->classes as $class) {
            if (
                ! str_starts_with(
                    $class->fileSubject->file,
                    base_path('vendor')
                )
            ) {
                $this->cleanClass($class);

                $class->php?->increment('max_method');
                $class->php?->increment('max_property');
            }
        }

        foreach ($this->classes as $class) {

            if (
                ! str_starts_with(
                    $class->fileSubject->file,
                    base_path('vendor')
                )
            ) {
                $class->save();

                $saved[$class->fileSubject->file] = str_replace(base_path(), '', $class->fileSubject->file);

                Php::createOrUpdatePhp($class);

                call_user_func($cb, $class);
            }
        }

        $php = Php::whereNotIn('file', $saved)
            //->whereNotNull('topic_type')
            ->get();

        foreach ($php as $item) {
            if (
                ! str_starts_with(
                    $item->file,
                    base_path('vendor')
                )
            ) {
                @ unlink(base_path($item->file));
            }
            $item->subjects()->delete();
            $item->delete();
            call_user_func($cbDel, $item->file);
        }

        foreach ($this->generatorInstances as $generatorInstance) {
            $generatorInstance->afterSave();
        }

        return $this;
    }

    /**
     * @param  ClassSubject  $subject
     * @return void
     */
    protected function cleanClass(ClassSubject $subject): void
    {
        /** @var null|Php $php */
        $php = $subject->php;
        if ($php) {
            $maxMethod = $php->max_method;

            $listMethods = $php->subjects()
                ->where('type', 'method')
                ->where('processed', '<', $maxMethod)
                ->get();
            /** @var PhpSubject $method */
            foreach ($listMethods as $method) {
                $subject->forgetMethod($method->name);
                $method->delete();
            }

            $maxProperty = $php->max_property;

            $listProperties = $php->subjects()
                ->where('type', 'property')
                ->where('processed', '<', $maxProperty)
                ->get();
            /** @var PhpSubject $property */
            foreach ($listProperties as $property) {
                $subject->forgetProperty($property->name);
                $property->delete();
            }
        }
    }

    /**
     * @param  string  $file
     * @param  ModelTopic|null  $modelTopic
     * @return AnonymousClassSubject
     * @throws Exception
     */
    public function anonymousClass(string $file, ModelTopic $modelTopic = null): AnonymousClassSubject
    {
        return $this->watchClass(
            'anonymousClass', $file, $modelTopic
        );
    }

    /**
     * @param  string  $class
     * @param  ModelTopic|null  $modelTopic
     * @return ClassSubject
     * @throws Exception
     */
    public function class(string $class, ModelTopic $modelTopic = null): ClassSubject
    {
        return $this->watchClass(
            'class', $class, $modelTopic
        );
    }

    /**
     * @param  string  $class
     * @param  ModelTopic|null  $modelTopic
     * @return InterfaceSubject
     * @throws Exception
     */
    public function interface(string $class, ModelTopic $modelTopic = null): InterfaceSubject
    {
        return $this->watchClass(
            'interface', $class, $modelTopic
        );
    }

    /**
     * @param  string  $class
     * @param  ModelTopic|null  $modelTopic
     * @return TraitSubject
     * @throws Exception
     */
    public function trait(string $class, ModelTopic $modelTopic = null): TraitSubject
    {
        return$this->watchClass(
            'trait', $class, $modelTopic
        );
    }

    /**
     * @param  string  $class
     * @param  ModelTopic|null  $modelTopic
     * @return EnumSubject
     * @throws Exception
     */
    public function enum(string $class, ModelTopic $modelTopic = null): EnumSubject
    {
        return $this->watchClass(
            'enum', $class, $modelTopic
        );
    }
}
