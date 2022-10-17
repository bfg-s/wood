<?php

namespace Bfg\Wood\Generators\ModelGenerator;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\Generators\GeneratorAbstract;
use Bfg\Wood\Generators\ModelGenerator;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\ModelRelation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpParser\Node\Expr;

/**
 * @property-read ModelGenerator $parent
 * @mixin Model
 */
class ModelRelationGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Model[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Model::all();
    }

    protected function relation()
    {
        foreach ($this->relations()->get() as $item) {

            if (method_exists($this, '__' . $item->type)) {
                $this->{'__' . $item->type}($item);
            } else {
                $this->__relation_method(
                    $item,
                    $this->__makeRealClass($item->related_model()->first()->class->class),
                    $item->foreign,
                    'id',
                );
            }
        }

        foreach ($this->related()->get() as $item) {

            if (method_exists($this, '__related_' . $item->type)) {
                $this->{'__related_' . $item->type}($item);
            } else {
                $this->__related_method(
                    $item,
                    $this->__makeRealClass($item->model()->first()->class->class),
                    'id',
                    $item->foreign,
                );
            }
        }
    }

    protected function __related_method(
        ModelRelation $relation,
        ...$args
    ): ClassMethodNode {

        //$cfg = config("wood.relation_types." . $relation->reverse_type);

        $method = $this->class->publicMethod([
            $relation->relation_class,
            $relation->reverse_name,
        ]);

        $method->return()
            ->this()
            ->func(
                $relation->type,
                ...$args
            );

        $method->comment(
            fn(DocSubject $doc
            ) => $doc->name('The reverse relation for "'.$this->table.'->'.$relation->model()->first()->table().'".')
        );

        return $method;
    }

    protected function __relation_method(
        ModelRelation $relation,
        ...$args
    ): ClassMethodNode {
        $method = $this->class->publicMethod([
            $relation->relation_class,
            $relation->name
        ]);

        $method->return()
            ->this()
            ->func(
                $relation->type,
                ...$args
            );

        $method->comment(
            fn(DocSubject $doc
            ) => $doc->name('The opposite relation for "'.$this->table.'->'.$relation->name.'".')
        );

        return $method;
    }

    protected function __makeRealClass(
        string $class
    ): ?Expr {
        return php()->real(
            Comcode::useIfClass($class, $this->class)."::class"
        );
    }
}
