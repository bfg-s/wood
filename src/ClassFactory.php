<?php

namespace Bfg\Wood;

use Bfg\Comcode\Subjects\AnonymousClassSubject;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\InterfaceSubject;
use Bfg\Comcode\Subjects\TraitSubject;
use ErrorException;

class ClassFactory
{
    /**
     * @var array|ClassSubject[]
     */
    protected array $classes = [];

    /**
     * @template Subject
     * @param  ClassSubject|Subject  $subject
     * @return Subject
     */
    public function watchClass(ClassSubject $subject): ClassSubject
    {
        if (isset($this->classes[$subject->fileSubject->file])) {

            return $this->classes[$subject->fileSubject->file];
        }

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

            $class->save();

            call_user_func($cb, $class);
        }

        return $this;
    }

    /**
     * @param $value
     * @return AnonymousClassSubject
     * @throws ErrorException
     */
    public function anonymousClass($value): AnonymousClassSubject
    {
        return $this->watchClass(
            \php()->anonymousClass($value)
        );
    }

    /**
     * @param $value
     * @return ClassSubject
     * @throws ErrorException
     */
    public function class($value): ClassSubject
    {
        return $this->watchClass(
            \php()->class($value)
        );
    }

    /**
     * @param $value
     * @return InterfaceSubject
     * @throws ErrorException
     */
    public function interface($value): InterfaceSubject
    {
        return $this->watchClass(
            \php()->interface($value)
        );
    }

    /**
     * @param $value
     * @return TraitSubject
     * @throws ErrorException
     */
    public function trait($value): TraitSubject
    {
        return $this->watchClass(
            \php()->trait($value)
        );
    }
}
