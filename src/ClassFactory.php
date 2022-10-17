<?php

namespace Bfg\Wood;

use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Nodes\ClassPropertyNode;
use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Comcode\Subjects\TraitSubject;
use Bfg\Wood\Models\Php;
use Bfg\Wood\Models\PhpSubject;
use ErrorException;

class ClassFactory
{
    /**
     * @var array|ClassSubject[]
     */
    protected array $classes = [];

    static int $maxMethod = 0;
    static int $maxProperty = 0;

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
        /** @var null|Php $php */
        $php = $subject->php;
        if ($php) {

            $subject = $php->subjects()
                ->where('name', $node->getName())
                ->first();

            if (! $subject) {

                if ($php->subjects()
                    ->where('type', 'method')->count()) {
                    $processed = $php->subjects()
                        ->where('type', 'method')
                        ->max('processed')+1;
                    if (! static::$maxMethod) {
                        static::$maxMethod = $processed;
                    }
                }

                $subject = $php->subjects()->create([
                    'name' => $node->getName(),
                    'type' => 'method',
                    'processed' => static::$maxMethod,
                ]);
            } else {
                $subject->increment('processed');
            }
        }
    }

    public function onPropertyCreate(
        ClassSubject $subject,
        ClassPropertyNode $node,
    ): void {
        /** @var null|Php $php */
        $php = $subject->php;
        if ($php) {

            $subject = $php->subjects()
                ->where('name', $node->getName())
                ->first();

            if (! $subject) {

                if ($php->subjects()
                    ->where('type', 'property')->count()) {
                    $processed = $php->subjects()
                        ->where('type', 'property')
                        ->max('processed')+1;
                    if (! static::$maxProperty) {
                        static::$maxProperty = $processed;
                    }
                }

                $subject = $php->subjects()->create([
                    'name' => $node->getName(),
                    'type' => 'property',
                    'processed' => static::$maxProperty,
                ]);
            } else {
                $subject->increment('processed');
            }
        }
    }

    /**
     * @template Subject
     * @param  ModelTopic  $modelTopic
     * @param  string  $type
     * @param  string  $value
     * @return Subject
     */
    public function watchClass(ModelTopic $modelTopic, string $type, string $value): ClassSubject
    {
        $subject = \php()->{$type}($value);

        if (isset($this->classes[$subject->fileSubject->file])) {

            return $this->classes[$subject->fileSubject->file];
        }

        $php = Php::findByTopic($modelTopic);

        if ($php) {
            $file = str_replace(base_path(), '', $subject->fileSubject->file);
            if ($file !== $php->file) {
                rename(
                    base_path($php->file),
                    base_path($file)
                );
                $subject = \php()->{$type}($value);
            }
        }

        $subject->php = $php;

        $subject->modelTopic = $modelTopic;

        return $this->classes[$subject->fileSubject->file]
            = $subject;
    }

    /**
     * @return $this
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function generate(): static
    {
        foreach (\Wood::getTopics() as $topic) {

            if ($generator = $topic::getGenerator()) {

                app()->make($generator);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function save(callable $cb): static
    {
        foreach ($this->classes as $class) {

            if (
                ! str_starts_with(
                    $class->fileSubject->file,
                    base_path('vendor')
                )
            ) {
                $this->cleanClass($class);

                $class->save();

                Php::createOrUpdatePhp($class);

                call_user_func($cb, $class);
            }
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
            $maxMethod = $php->subjects()
                ->where('type', 'method')
                ->max('processed');
            $listMethods = $php->subjects()
                ->where('type', 'method')
                ->where('processed', '<', $maxMethod)
                ->get();
            /** @var PhpSubject $method */
            foreach ($listMethods as $method) {
                $subject->forgetMethod($method->name);
                //dump("Delete method: " . $method->name . ", Max: " . $maxMethod . ", Current: " . $method->processed);
                $method->delete();
            }
            $maxProperty = $php->subjects()
                ->where('type', 'property')
                ->max('processed');
            $listProperties = $php->subjects()
                ->where('type', 'property')
                ->where('processed', '<', $maxProperty)
                ->get();
            /** @var PhpSubject $property */
            foreach ($listProperties as $property) {
                $subject->forgetProperty($property->name);
                //dump("Delete property: " . $property->name . ", Max: " . $maxProperty . ", Current: " . $property->processed);
                $property->delete();
            }
        }
    }

    /**
     * @param $value
     * @param  ModelTopic  $modelTopic
     * @return AnonymousClassSubject
     */
    public function anonymousClass($value, ModelTopic $modelTopic): AnonymousClassSubject
    {
        return $this->watchClass(
            $modelTopic, 'anonymousClass', $value
        );
    }

    /**
     * @param $value
     * @param  ModelTopic  $modelTopic
     * @return ClassSubject
     */
    public function class($value, ModelTopic $modelTopic): ClassSubject
    {
        return $this->watchClass(
            $modelTopic, 'class', $value
        );
    }

    /**
     * @param $value
     * @param  ModelTopic  $modelTopic
     * @return InterfaceSubject
     */
    public function interface($value, ModelTopic $modelTopic): InterfaceSubject
    {
        return $this->watchClass(
            $modelTopic, 'interface', $value
        );
    }

    /**
     * @param $value
     * @param  ModelTopic  $modelTopic
     * @return TraitSubject
     */
    public function trait($value, ModelTopic $modelTopic): TraitSubject
    {
        return $this->watchClass(
            $modelTopic, 'trait', $value
        );
    }
}
