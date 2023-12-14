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
                    $this->__makeRealClass($item->related_model->class->class),
                    'id',
                    $item->foreign_id,
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
                    $item->foreign_id,
                    'id',
                );
            }
        }
    }

    protected function __belongsToMany(ModelRelation $relation)
    {
        $related = $relation->related_model()->first();
        $this->__relation_method(
            $relation,
            $this->__makeRealClass($related->class->class),
            $this->table() . '_' . $relation->name,
            $this->foreign_id,
            $related->foreign_id,
        );
    }

    protected function __related_belongsToMany(ModelRelation $relation)
    {
        $related = $relation->model()->first();
        $this->__related_method(
            $relation,
            $this->__makeRealClass($relation->model->class->class),
            $related->table() . '_' . $relation->name,
            $this->foreign_id,
            $related->foreign_id,
        );
    }

    protected function __morphOne(ModelRelation $relation)
    {
        $related = $relation->related_model()->first();
        $this->__relation_method(
            $relation,
            $this->__makeRealClass($related->class->class),
            $relation->able,
        );
    }

    protected function __related_morphOne(ModelRelation $relation)
    {
        //$related = $relation->model()->first();
        $this->__related_method(
            $relation,
            $relation->able,
        );
    }

    protected function __related_method(
        ModelRelation $relation,
        ...$args
    ): ClassMethodNode {

        //$cfg = config("wood.relation_types." . $relation->reverse_type);

        $method = $this->class->publicMethod([
            $relation->reverse_type_class,
            $relation->reverse_type === 'morphTo'
                ? $relation->able
                : $relation->reverse_name,
        ]);

        $method->return()
            ->this()
            ->func(
                $relation->reverse_type,
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
            $relation->type === 'morphTo'
                ? $relation->able
                : $relation->name
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
