<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\Models\Resource;
use Bfg\Wood\Models\Topic;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use JsonSerializable;

/**
 * @mixin Resource
 */
class ResourceGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Resource::all();
    }

    protected function extends()
    {
        $this->class->extends(
            JsonResource::class
        );
    }

    protected function toArray()
    {
        $this->class->use(Arrayable::class);
        $this->class->use(JsonSerializable::class);
        $this->class->when(
            $this->class->notExistsMethod('toArray'),
            fn() => $this->class
                ->publicMethod('toArray')
                ->expectParams('request')
                ->comment(
                    fn (DocSubject $doc)
                    => $doc->name('Transform the resource into an array.')
                        ->tagParam(Request::class, 'request')
                        ->tagReturn('array|Arrayable|JsonSerializable')
                )
                ->return()
                ->staticCall('parent', 'toArray', php('request'))
        );
    }
}
