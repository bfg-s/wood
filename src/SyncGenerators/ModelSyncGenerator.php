<?php

namespace Bfg\Wood\SyncGenerators;

use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\Topic;
use Bfg\Attributes\Attributes;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;
use ReflectionException;

/**
 * @mixin ReflectionClass
 */
class ModelSyncGenerator extends SyncGeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Attributes::new()
            ->wherePath(app_path('Models'))
            ->classes()
            ->filter(
                fn (ReflectionClass $class)
                => $class->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)
            );
    }

    /**
     * @return Model
     * @throws ReflectionException
     */
    protected function existsOrCreate(): Model
    {
        /** @var \Illuminate\Database\Eloquent\Model $class */
        $class = $this->newInstance();

        $model = Model::where('class', $this->getName())->first();

        if (! $model) {

            $model = Model::create([
                'class' => $this->getName(),
                'order' => Model::count(),
            ]);
        }

        if ($this->isSubclassOf(\Illuminate\Foundation\Auth\User::class)) {

            $model->update([
                'auth' => 1
            ]);
        }

        if (! $class::UPDATED_AT || ! $class->timestamps) {

            $model->update([
                'updated' => 0
            ]);
        }

        if (! $class::CREATED_AT || ! $class->timestamps) {

            $model->update([
                'updated' => 0
            ]);
        }

        if ($this->hasMethod('bootSoftDeletes')) {

            $model->update([
                'deleted' => 1
            ]);
        }

        return $model;
    }

    /**
     * @param  Model  $model
     * @return Model
     * @throws ReflectionException
     */
    protected function fillable(Model $model): Model
    {
        /** @var \Illuminate\Database\Eloquent\Model $class */
        $class = $this->newInstance();

        $fillable = $class->getFillable();
        $casts = $class->getCasts();

        foreach ($fillable as $fieldName) {
            $cast = $casts[$fieldName] ?? 'string';
            $field = $model->fields()->where('name', $fieldName)->first();
            if (! $field) {
                $field = $model->fields()->create(['name' => $fieldName]);
                $field->update([
                    'cast' => $cast,
                ]);
            }
        }

        return $model;
    }

    protected function modelTraits(Model $model): Model
    {
        foreach ($this->getTraits() as $trait) {

            $traitClass = $model->traits()->where('class', $trait->getName())->first();

            if (! $traitClass) {
                $model->traits()->create([
                    'class' => $trait->getName()
                ]);
            }
        }

        return $model;
    }

    protected function modelImplements(Model $model): Model
    {
        foreach ($this->getInterfaceNames() as $interface) {

            $traitClass = $model->implements()->where('class', $interface)->first();

            if (! $traitClass) {
                $model->implements()->create([
                    'class' => $interface
                ]);
            }
        }

        return $model;
    }
}
