<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Generators\ModelGenerator\ModelFactoryGenerator;
use Bfg\Wood\Generators\ModelGenerator\ModelMigrationGenerator;
use Bfg\Wood\Generators\ModelGenerator\ModelObserverGenerator;
use Bfg\Wood\Generators\ModelGenerator\ModelRelationGenerator;
use Bfg\Wood\Models\Factory;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\ModelField;
use Bfg\Wood\Models\ModelImplement;
use Bfg\Wood\Models\ModelRelation;
use Bfg\Wood\Models\ModelTrait;
use Bfg\Wood\Models\Topic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @mixin Model
 */
class ModelGenerator extends GeneratorAbstract
{
    /**
     * @var array|string[]
     */
    protected array $child = [
        ModelFactoryGenerator::class,
        ModelRelationGenerator::class,
        ModelMigrationGenerator::class,
        ModelObserverGenerator::class,
    ];

    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Model::all();
    }

    protected function extends()
    {
        if (
            $this->auth
        ) {
            if ($this->class->notExistsExtends('Authenticatable')) {
                $this->class->use(
                    'Illuminate\Foundation\Auth\User as Authenticatable'
                );
                $this->class->extends(
                    'Authenticatable'
                );
            }
        } else {
            if ($this->class->notExistsExtends(\Illuminate\Database\Eloquent\Model::class)) {
                $this->class->extends(
                    Model::class
                );
            }
        }
    }

    /**
     * Add implements
     * @return void
     */
    protected function implement(): void
    {
        $this->implements()->get()->map(
            fn (ModelImplement $implement)
            => $this->class->implement($implement->class->class)
        );
    }

    /**
     * Add traits
     * @return void
     */
    protected function trait(): void
    {
        $this->traits()->get()->map(
            fn (ModelTrait $trait)
            => $this->class->trait($trait->class->class)
        );

        if ($this->deleted) {
            $this->class->trait(
                SoftDeletes::class
            );
        } else {
            $this->class->forgetTrait(
                SoftDeletes::class
            );
        }
        if (Factory::where('model_id', $this->id)->exists()) {
            $this->class->trait(
                HasFactory::class
            );
        }
    }

    /**
     * Add constants
     * @return void
     */
    protected function constant(): void
    {
        if (
            $this->created
            && !$this->updated
        ) {
            if ($this->class->notExistsConst('UPDATED_AT')) {
                $this->class->publicConst('UPDATED_AT', php()->real())
                    ->comment(
                        fn(DocSubject $doc) => $doc->name('The name of the "updated at" column.')
                            ->tagReturn('bool')
                    );
            }
        } else {
            if (
                $this->updated
                && !$this->created
            ) {
                if ($this->class->notExistsConst('CREATED_AT')) {
                    $this->class->publicConst('CREATED_AT', php()->real())
                        ->comment(
                            fn(DocSubject $doc) => $doc->name('The name of the "created at" column.')
                                ->tagReturn('bool')
                        );
                }
            } else {
                if (
                    $this->created
                    && !$this->updated
                ) {
                    $this->class->forgetConst('CREATED_AT');
                } else {
                    if (
                        $this->updated
                        && !$this->created
                    ) {
                        $this->class->forgetConst('UPDATED_AT');
                    } else {
                        if (
                            ($this->updated && $this->created)
                            || (!$this->updated && !$this->created)
                        ) {
                            $this->class->forgetConst('CREATED_AT');
                            $this->class->forgetConst('UPDATED_AT');
                        }
                    }
                }
            }
        }
    }

    /**
     * Add primary variables
     * @return void
     */
    protected function foreign(): void
    {
        if ($this->increment && $this->foreign != 'id') {
            $this->class->protectedProperty('primaryKey', php()->real($this->foreign))
                ->comment(
                    fn(DocSubject $doc) => $doc->name('The primary key for the model.')
                        ->tagVar('string')
                );
        } else {
            $this->class->forgetProperty('primaryKey');
        }
    }

    /**
     * Apply timestamps
     * @return void
     */
    protected function timestamps(): void
    {
        if (
            !$this->created
            && !$this->updated
        ) {
            if ($this->class->notExistsMethod('timestamps')) {
                $this->class->publicProperty('timestamps', false)
                    ->comment(
                        fn(DocSubject $doc) => $doc->name('Indicates if the model should be timestamped.')
                            ->tagReturn('bool')
                    );
            }
        } else {
            $this->class->forgetProperty('timestamps');
        }
    }

    /**
     * @return void
     */
    protected function fillable(): void
    {
        $names = $this->fields()
            ->pluck('name');

        $foreigns = $this->relations()
            ->where('type', '!=', 'belongsToMany')
            ->where('type', '!=', 'morphTo')
            ->where('type', '!=', 'morphOne')
            ->where('type', '!=', 'morphMany')
            ->where('type', '!=', 'morphToMany')
            ->where('type', '!=', 'morphedByMany')
            ->get()
            ->map(fn (ModelRelation $relation) => $relation->foreign_id);

        $morphForeigns = $this->related()
            ->whereIn('type', [
                'morphTo', 'morphOne', 'morphMany', 'morphToMany', 'morphedByMany'
            ])
            ->get()
            ->pluck('able')
            ->map(
                fn (string $able)
                => [$able.'_type', $able.'_id']
            )->collapse()->unique();

        $names = $names->merge($foreigns)
            ->merge($morphForeigns);

        if ($names->isNotEmpty()) {
            $this->class->protectedProperty(
                'fillable', $names->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The attributes that are mass assignable.')
                    ->tagVar('array<int, string>')
            );
        }
    }

    /**
     * @return void
     */
    protected function hidden(): void
    {
        $names = $this->fields()
            ->where('hidden', true)
            ->pluck('name');


        if ($names->isNotEmpty()) {

            $this->class->protectedProperty(
                'hidden', $names->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The attributes that should be hidden for serialization.')
                    ->tagVar('array<int, string>')
            );
        }
    }

    /**
     * @return void
     */
    protected function casts(): void
    {
        $casts = collect($this->fields()
            ->unless(config('wood.publish_string_casts'), fn ($q) => $q->where('cast', '!=', 'string'))
            ->get()
            ->mapWithKeys(function (ModelField $field) {
                $cast = $field->cast;

                if (str_ends_with($cast, 'Cast')) {
                    $cast = Comcode::useIfClass(
                        $field->cast_class->class,
                        $this->class
                    ) . '::class';
                    $field->cast_class
                        ->method('public', 'get')
                        ->expectParams(
                            ['model', null, null],
                            ['key', null, 'string'],
                            ['value', null, null],
                            ['attributes', null, 'array'],
                        )->comment(
                            fn(DocSubject $doc) => $doc->name('Cast the given value.')
                                ->tagParam(\Illuminate\Database\Eloquent\Model::class, 'model')
                                ->tagParam('string', 'key')
                                ->tagParam('mixed', 'value')
                                ->tagParam('array', 'attributes')
                                ->tagReturn('mixed')
                        )->return('value');
                    $field->cast_class
                        ->method('public', 'set')
                        ->expectParams(
                            ['model', null, null],
                            ['key', null, 'string'],
                            ['value', null, null],
                            ['attributes', null, 'array'],
                        )->comment(
                            fn(DocSubject $doc) => $doc->name('Prepare the given value for storage.')
                                ->tagParam(\Illuminate\Database\Eloquent\Model::class, 'model')
                                ->tagParam('string', 'key')
                                ->tagParam('mixed', 'value')
                                ->tagParam('array', 'attributes')
                                ->tagReturn('mixed')
                        )->return('value');
                } else if (str_ends_with($cast, 'Enum')) {
                    $cast = Comcode::useIfClass(
                            $field->enum_class->class,
                            $this->class
                        ) . '::class';
                }

                return [$field->name => $cast];
            }));

        $foreigns = $this->relations()
            ->where('type', '!=', 'belongsToMany')
            ->where('type', '!=', 'morphTo')
            ->where('type', '!=', 'morphOne')
            ->where('type', '!=', 'morphMany')
            ->where('type', '!=', 'morphToMany')
            ->where('type', '!=', 'morphedByMany')
            ->get()
            ->map(fn (ModelRelation $relation) => $relation->related_model->foreign_id)
            ->mapWithKeys(fn ($i) => [$i => 'int']);

        $casts = $casts->merge($foreigns);

        if ($casts->isNotEmpty()) {

            $this->class->protectedProperty(
                'casts', $casts->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The attributes that should be cast.')
                    ->tagVar('array<string, string>')
            );
        }
    }

    /**
     * @return void
     */
    protected function with(): void
    {
        $names = $this->relations()
            ->where('with', true)
            ->pluck('name');

        if ($names->isNotEmpty()) {
            $this->class->protectedProperty(
                'with', $names->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The relations to eager load on every query.')
                    ->tagVar('array<int, string>')
            );
        }
    }

    /**
     * @return void
     */
    protected function withCount(): void
    {
        $names = $this->relations()
            ->where('with_count', true)
            ->pluck('name');

        if ($names->isNotEmpty()) {
            $this->class->protectedProperty(
                'withCount', $names->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The relationship counts that should be eager loaded on every query.')
                    ->tagVar('array<int, string>')
            );
        }
    }

    protected function finish(): void
    {
        $provider = app(ClassFactory::class)
            ->class("App\\Providers\\BfgWoodProvider");

        $observers = [];

        foreach (Model::all() as $item) {
            foreach ($item->observers as $observer) {
                $observers[
                    Comcode::useIfClass($item->class->class, $provider) . '::class'
                ][] = Comcode::useIfClass($observer->class->class, $provider) . '::class';
            }
        }

        $provider->protectedProperty(
            ['array', 'observers'], $observers
        );
    }
}
