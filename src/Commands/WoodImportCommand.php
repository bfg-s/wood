<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\SubjectAbstract;
use Bfg\Wood\ArrayFactory;
use Bfg\Wood\ClassFactory;
use Bfg\Wood\ImportFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class WoodImportCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:import";

    /**
     * @var string
     */
    protected $description = "Run import all datas to json file";

    /**
     * @return int
     */
    public function handle(): int
    {
        app(ImportFactory::class)
            ->parse()
            ->save();

        $this->info('Import is finished!');

        return 0;
    }
}
