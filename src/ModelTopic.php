<?php

namespace Bfg\Wood;

use Bfg\Wood\Casts\AnonymousClassCast;
use Bfg\Wood\Casts\ClassCast;
use Bfg\Wood\Casts\InterfaceCast;
use Bfg\Wood\Casts\PJsCast;
use Bfg\Wood\Casts\TraitCast;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

abstract class ModelTopic extends Model
{
    /**
     * @var string
     */
    protected $connection = 'wood';

    /**
     * @var array
     */
    protected static array $iteration = [];

    /**
     * @var string|null
     */
    protected static ?string $generator = null;

    /**
     * @var array
     */
    public static array $schema = [];

    /**
     * @var array
     */
    public static array $list = [];

    /**
     * @var string|null
     */
    public ?string $parent = null;

    /**
     * @var string|null
     */
    public ?string $modelName = null;

    /**
     * @var string|null
     */
    public ?string $modelDescription = null;

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-folder';

    /**
     * @var array
     */
    public array $settings = [];

    /**
     * @param  array  $attributes
     * @throws Exception
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        foreach (static::$schema as $field => $config) {

            $this->fillable[] = $field;
            $this->casts[$field] = $config['cast'] ?? 'string';
        }
    }

    /**
     * @throws Exception
     */
    protected static function boot()
    {
        parent::boot();
        static::schemaBuild();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    /**
     * @throws Exception
     */
    protected static function schemaBuild()
    {
        foreach (static::$schema as $field => $config) {

            static::$schema[$field]
                = static::createSchemaRow($field, $config);
        }

        static::$schema['order'] = static::createSchemaRow(
            'order', ['integer', 'default' => 0], false
        );
    }

    /**
     * @throws Exception
     */
    protected static function createSchemaRow($field, $config, bool $visible = true): array
    {
        $rs = (array)$config;

        if (isset($rs[0]) && is_string($rs[0])) {
            $rs['type'] = $rs[0];
            unset($rs[0]);
        }

        $nullable = array_key_exists('nullable', $rs)
            && $rs['nullable'];

        $unique = array_key_exists('unique', $rs)
            && $rs['unique'];

        $rs['methods'] = $rs['methods'] ?? [];
        $rs['type'] = $rs['type'] ?? 'string';
        $rs['first_type'] = $rs['type'];

        if (isset($rs['enum']) && $rs['enum']) {
            $rs['type'] = 'enum';
            $rs['params'] = [$rs['enum']];
            $rs['variants'] = $rs['enum'];
            unset($rs['enum']);
        }

        if (array_key_exists('default', $rs)) {
            $rs['methods']['default'] = $rs['default'];
        }

        if (! isset($rs['cast'])) {
            $rs['cast'] = match ($rs['type']) {
                'class' => ClassCast::class,
                'interface' => InterfaceCast::class,
                'trait' => TraitCast::class,
                'pjs' => PJsCast::class,
                'anonymousClass' => AnonymousClassCast::class,
                'enum', 'text', 'any', 'char', 'tinyText', 'mediumText', 'longText',
                => 'string',
                'tinyInteger', 'smallInteger', 'mediumInteger', 'bigInteger',
                'unsignedInteger', 'unsignedTinyInteger', 'unsignedSmallInteger',
                'unsignedMediumInteger', 'unsignedBigInteger'
                => 'integer',
                'boolean'
                => 'bool',
                'json', 'jsonb'
                => 'array',
                'date', 'dateTime', 'dateTimeTz', 'time', 'timeTz', 'timestampTz'
                => 'timestamp',
                'float', 'decimal', 'unsignedFloat', 'unsignedDouble', 'unsignedDecimal'
                => 'double',
                default
                => $rs['type'],
            };
        }

        $nullable = match ($rs['type']) {
            'any' => true,
            default => $nullable,
        };

        $rs['type'] = match ($rs['type']) {
            'bool' => 'boolean',
            'array', 'class', 'interface', 'trait', 'anonymousClass', 'pjs' => 'json',
            'int' => 'integer',
            'any' => 'text',
            default => $rs['type'],
        };

        if (
            $rs['cast'] === 'array'
            && ! array_key_exists('default', $rs['methods'])
        ) {
            $rs['methods']['default'] = [];
            $rs['default'] = [];
        }

        $self = new static();

        ModelTopic::$list[static::class] = static::class;

        if (
            method_exists($self, $field)
            && ! method_exists(Model::class, $field)
        ) {
            $relation = $self->{$field}();
            if (
                ! $relation instanceof HasMany
                && ! $relation instanceof HasOne
            ) {
                throw new Exception('Relation ['.$field.'] must be HasMany or HasOne');
            }
            $relatedModel = $relation->getModel();
            if (! $relatedModel instanceof ModelTopic) {

                throw new Exception('Relation model must be a ModelTopic');
            }
            $relatedParams = [
                'type' => 'foreignId',
                //'nullable' => isset($rs['methods']['nullable']) || $nullable,
                'methods' => []
            ];
//            if (isset($rs['methods']['nullable']) || $nullable) {
//            }
            $relatedParams['nullable'] = [];
            $relatedParams['constrained'] = [];
            $relatedParams['cascadeOnUpdate'] = [];
            $relatedParams['cascadeOnDelete'] = [];
            $relatedParams['nullOnDelete'] = [];

            $foreignKey = $relation->getQualifiedForeignKeyName();
            $foreignKey = preg_replace('/.*\.(.*)/', '$1', $foreignKey);
            if ($foreignKey !== 'id') {
                $relatedModel::${'schema'}[$foreignKey]
                    = static::createSchemaRow($foreignKey, $relatedParams, false);
            }
            $localKey = $relation->getLocalKeyName();
            $localKey = preg_replace('/.*\.(.*)/', '$1', $localKey);
            if ($localKey !== 'id') {
                static::$schema[$localKey]
                    = static::createSchemaRow($localKey, $relatedParams, false);
            }

            $rs['schema'] = $relatedModel::class;
            $rs['schema_type'] = class_basename($relation);
        } else {
            $rs['schema'] = null;
        }

        if (! isset($rs['params'])) {
            $rs['params'] = [];
        }

        if (! isset($rs['methods'])) {
            $rs['methods'] = [];
        }

        if ($nullable) {
            $rs['methods']['nullable'] = [];
            $rs['nullable'] = true;
        }

        if ($unique) {
            $rs['methods']['unique'] = [];
            $rs['unique'] = true;
        }

        if (
            ! isset($rs['system'])
            || ! is_bool($rs['system'])
        ) {
            $rs['system'] = false;
        }

        $rs['visible'] = $visible;
        if (! isset(static::$iteration[static::class])) {
            static::$iteration[static::class] = 0;
        }
        $rs['index'] = static::$iteration[static::class]++;
        $rs['field'] = $field;

        return $rs;
    }

    /**
     * @return array
     */
    public static function seeds(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public static function getGenerator(): ?string
    {
        return static::$generator;
    }

    /**
     * @param  string  $class
     * @return void
     */
    public static function setGenerator(string $class): void
    {
        static::$generator = $class;
    }

    public function __get($key)
    {
        if (
            method_exists($this, $key)
            && ! method_exists(ModelTopic::class, $key)
            && isset(static::$schema[$key])
        ) {

            if (! $this->relationLoaded($key)) {
//                $relation = $this->{$key}();

//                if ($relation instanceof HasOne) {
//                    return $relation->first();
//                } else {
//                    return $relation->get();
//                }
                $this->load($key);
            }
            return $this->getRelation($key);
        }

        return parent::__get($key); // TODO: Change the autogenerated stub
    }
}
