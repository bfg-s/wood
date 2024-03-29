<?php

return [
    /**
     * Connection driver for wood database
     */
    'connection' => [
        'driver' => 'sqlite',
        'url' => null,
        'database' => database_path('wood.sqlite'),
        'prefix' => '',
        'foreign_key_constraints' => true
    ],

    /**
     * Definition of relation types
     */
    'relation_types' => [
        'hasMany' => [
            'class' => \Illuminate\Database\Eloquent\Relations\HasMany::class,
            'reverses' => 'hasOne',
            'declinations' => 'plural',
        ],
        'belongsToMany' => [
            'class' => \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            'reverses' => 'belongsToMany',
            'declinations' => 'plural',
        ],
        'hasOne' => [
            'class' => \Illuminate\Database\Eloquent\Relations\HasOne::class,
            'reverses' => 'hasMany',
            'declinations' => 'singular',
        ],
        'belongsTo' => [
            'class' => \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            'reverses' => 'hasMany',
            'declinations' => 'singular',
        ],
        'morphTo' => [
            'class' => \Illuminate\Database\Eloquent\Relations\MorphTo::class,
            'reverses' => 'morphOne',
            'declinations' => 'plural',
        ],
        'morphOne' => [
            'class' => \Illuminate\Database\Eloquent\Relations\MorphOne::class,
            'reverses' => 'morphTo',
            'declinations' => 'singular',
        ],
        'morphMany' => [
            'class' => \Illuminate\Database\Eloquent\Relations\MorphMany::class,
            'reverses' => 'morphedByMany',
            'declinations' => 'plural',
        ],
        'morphToMany' => [
            'class' => \Illuminate\Database\Eloquent\Relations\MorphToMany::class,
            'reverses' => 'morphedByMany',
            'declinations' => 'plural',
        ],
        'morphedByMany' => [
            'class' => \Illuminate\Database\Eloquent\Relations\MorphToMany::class,
            'reverses' => 'morphToMany',
            'declinations' => 'plural',
        ],
    ],

    /**
     * Add string casts to general cast list
     */
    'publish_string_casts' => false,

    /**
     * Prepend date for migration file name
     */
    'migration_prepend' => '2023_01_01',
];
