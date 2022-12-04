<?php

namespace Bfg\Wood\Models;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Generators\FactoryGenerator;
use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $count
 * @property-read Model $model
 * @property-read FactoryLine[]|Collection $lines
 * @property-read ClassSubject $class
 */
class Factory extends ModelTopic
{
    /**
     * @var array|string[]
     */
    protected static array $generators = [
        'general' => FactoryGenerator::class
    ];

    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-industry';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Factories';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The model factories';

    /**
     * @var array
     */
    public static array $schema = [
        'model' => [
            'unique' => true,
            'prepend' => "Database\\Factories\\",
            'select' => 'class', // select - modifier, class - field name for selection
            'info' => 'The model for which the factory will be created',
        ],
        'count' => [
            'int',
            'default' => 1,
            'info' => 'The count of factory creating rows',
            'regexp' => '^\d*$',
        ],
        'lines' => [],
    ];

    /**
     * @return HasOne
     */
    public function model(): HasOne
    {
        return $this->hasOne(Model::class, 'id', 'model_id');
    }

    /**
     * @return HasMany
     */
    public function lines(): HasMany
    {
        return $this->hasMany(FactoryLine::class);
    }

    /**
     * @return ClassSubject
     */
    public function getClassAttribute(): ClassSubject
    {
        return app(ClassFactory::class)
            ->class(
                "Database\\Factories\\"
                . class_basename($this->model->class->class) . "Factory",
                $this
            );
    }
}
