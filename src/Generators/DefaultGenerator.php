<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\Models\Topic;
use Bfg\Wood\WoodProvider;
use Illuminate\Support\Collection;

class DefaultGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return collect(true);
    }

    protected function defineClass(): ClassSubject
    {
        return app(ClassFactory::class)
            ->class("App\\Providers\\BfgWoodProvider");
    }

    protected function extends(ClassSubject $subject): ClassSubject
    {
        $subject->extends(WoodProvider::class);

        return $subject;
    }

    protected function makeMethods(ClassSubject $subject)
    {
//        $subject->publicMethod(['void', 'register'])->comment(
//            fn (DocSubject $doc)
//            => $doc->name('Register any application services.')
//                ->tagReturn('void')
//        );
//        $subject->publicMethod(['void', 'boot'])->comment(
//            fn (DocSubject $doc)
//            => $doc->name('Bootstrap any application services.')
//                ->tagReturn('void')
//        );
    }
}
