<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Falsework\Topic\ModelFieldTopic;
use Bfg\Wood\Generators\ModelGenerator\ModelFactoryGenerator;
use Bfg\Wood\Generators\ModelGenerator\ModelRelationGenerator;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\ModelField;
use Bfg\Wood\Models\ModelImplement;
use Bfg\Wood\Models\ModelTrait;
use Bfg\Wood\Models\Topic;
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

        foreach ($this->constants()->get() as $constant) {
            if ($this->class->notExistsConst($constant->name)) {
                $this->class->const(
                    $constant->modifier ?? 'public',
                    $constant->name,
                    $constant->value
                );
            }
        }
    }

    /**
     * Add primary variables
     * @return void
     */
    protected function foreign(): void
    {
        if ($this->increment && $this->increment != 'id') {
            $this->class->protectedProperty('primaryKey', php()->real())
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
            ->where('hidden', false)
            ->pluck('name');

        if ($names->isNotEmpty()) {
            $this->class->protectedProperty(
                'fillable', $names->toArray()
            )->comment(
                fn(DocSubject $doc) => $doc->name('The attributes that are mass assignable.')
                    ->tagVar('array<int, string>')
            );
        } else {
            $this->class->forgetProperty('fillable');
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
        } else {
            $this->class->forgetProperty('hidden');
        }
    }

    /**
     * @return void
     */
    protected function casts(): void
    {
        $this->class->protectedProperty(
            'casts',
            $this->fields()
                ->where('cast', '!=', 'string')
                ->get()
                ->mapWithKeys(
                    fn(ModelField $field) => [$field->name => $field->cast]
                )
                ->toArray()
        )->comment(
            fn(DocSubject $doc) => $doc->name('The attributes that should be cast.')
                ->tagVar('array<string, string>')
        );
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
        } else {
            $this->class->forgetProperty('with');
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
        } else {
            $this->class->forgetProperty('withCount');
        }
    }

    /**
     * @return void
     */
    protected function property(): void
    {
        foreach ($this->properties()->get() as $property) {
            $this->class->property(
                $property->modifier ?? 'public',
                $property->name,
                $property->value
            );
        }
    }
}