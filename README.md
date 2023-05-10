# Extension laravel wood

## Install
```bash
composer require bfg/wood
```

## Description
Laravel's advanced framework controller for a code generation,
change your framework quickly and easily.
Automate all the same tasks with best code generator.

## Json mode demo
[![Wood json demo](https://github.com/bfg-s/wood/blob/32fb956353772ee474487569c5af060882e2195b/assets/Bfg_wood_demo_video.gif "Wood json demo")](https://github.com/bfg-s/wood/blob/32fb956353772ee474487569c5af060882e2195b/assets/Bfg_wood_demo_video.gif "Wood json demo")

## Quick start
In order to start generating in json format, run the `php artisan wood:install` command in order to create the generator database, then create a json file using the `php artisan wood:import` command, after which in your folder `database` file `wood.json` will appear. In it, you describe your files according to the scheme.

### Commands
 * `php artisan wood:install` - Set the initial database for the generator
 * `php artisan wood:run` - Start generation process
 * `php artisan wood:build` - Build data from `JSON` file
 * `php artisan wood:import` - Import all data from table and create `JSON` file
 * `php artisan wood:sync` - Synchronize primary data of existing models into tables

### JSON structure
 * `<value>` - Data by default
```json
{
    "models": [
        {
            "class": "string",
            "foreign": "string<id>",
            "increment": "bool<true>",
            "auth": "bool<false>",
            "created": "bool<true>",
            "updated": "bool<true>",
            "deleted": "bool<true>",
            "migration": "bool<true>",
            "fields": [
                {
                    "name": "string",
                    "cast": "string<string>",
                    "type": "string<string>",
                    "type_parameters": "array<[]>",
                    "has_default": "bool<false>",
                    "default": "string<null>",
                    "hidden": "bool<false>",
                    "nullable": "bool<false>",
                    "unique": "bool<false>",
                    "index": "bool<false>",
                    "comment": "string<null>",
                    "type_details": "array<[]>"
                }
            ],
            "relations": [
                {
                    "related_model": "class",
                    "name": "string<null>",
                    "type": "string<hasOne>",
                    "reverse_name": "string<null>",
                    "reverse_type": "string<hasMany>",
                    "able": "string<null>",
                    "with": "bool<false>",
                    "with_count": "bool<false>",
                    "nullable": "bool<false>",
                    "cascade_on_update": "bool<true>",
                    "cascade_on_delete": "bool<true>",
                    "null_on_delete": "bool<false>"
                }
            ],
            "observers": [
                {
                    "class": "string",
                    "events": "array"
                }
            ],
            "traits": [
                {
                    "class": "string"
                }
            ],
            "implements": [
                {
                    "class": "string"
                }
            ]
        }
    ],
    "events": [
        {
            "class": "string",
            "listeners": [
                {
                    "row": "string"
                }
            ]
        }
    ],
    "controllers": [
        {
            "class": "string",
            "methods": [
                {
                    "row": "string",
                    "event": "class"
                }
            ]
        }
    ],
    "factories": [
        {
            "model": "class",
            "lines": [
                {
                    "field": "string",
                    "php": "string<null>"
                }
            ]
        }
    ],
    "seeds": [
        {
            "class": "string",
            "model": "class",
            "factory": "bool<false>",
            "count": "int<1>",
            "rows": [
                {
                    "row": "array"
                }
            ]
        }
    ],
    "requests": [
        {
            "class": "string",
            "access": "string<'true'>",
            "rules": [
                {
                    "name": "string",
                    "rules": "array"
                }
            ]
        }
    ],
    "resources": [
        {
            "class": "string"
        }
    ]
}
```

## Application documentation
We have extensive [documentation](https://wood.veskod.com/documentation/wood-application/install) in which you can get acquainted with the bfg wood.

## Application Demo
[![Wood demo](https://github.com/bfg-s/wood/blob/30f968b7b50d42675e441de6d98b06c34d216052/assets/wood-gif.gif "Wood demo")](https://github.com/bfg-s/wood/blob/30f968b7b50d42675e441de6d98b06c34d216052/assets/wood-gif.gif "Wood demo")

## More details.
[https://wood.veskod.com/](https://wood.veskod.com/)
