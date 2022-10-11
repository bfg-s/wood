<?php

namespace Bfg\Wood\Generators;

use Bfg\Wood\Models\Topic;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

abstract class GeneratorAbstract
{
    /**
     * Depends child generators
     * @var array|GeneratorAbstract[]
     */
    protected array $prependChild = [];

    /**
     * Child generators
     * @var array|GeneratorAbstract[]
     */
    protected array $child = [];

    /**
     * @var mixed|null
     */
    protected mixed $current = null;

    /**
     * @param  GeneratorAbstract|null  $parent
     * @throws BindingResolutionException
     */
    public function __construct(
        protected ?GeneratorAbstract $parent = null
    ) {
        foreach ($this->prependChild as $key => $item) {

            $this->prependChild[$key] = app()->make($item, [
                'parent' => $this
            ]);
        }

        foreach ($this->collection() as $item) {
            $this->current = $item;
            foreach (get_class_methods($this) as $stackName) {
                if (
                    !str_starts_with($stackName, '__')
                    && $stackName !== 'finish'
                    && $stackName !== 'collection'
                ) {
                    call_user_func([$this, $stackName], $item);
                }
            }
        }

        foreach ($this->child as $key => $item) {

            $this->child[$key] = app()->make($item, [
                'parent' => $this
            ]);
        }

        $this->finish();
    }

    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    abstract protected function collection(): Collection|array;

    /**
     * Finish generate method
     * @return void
     */
    protected function finish(): void
    {

    }

    /**
     * @param  string  $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->current->{$name}(...$arguments);
    }

    /**
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->current->{$name};
    }
}
