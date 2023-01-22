<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class WoodSyncCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:sync";

    /**
     * @var string
     */
    protected $description = "Run wood sync wit exists code";

    /**
     * @throws BindingResolutionException
     */
    public function handle(): int
    {
        app(ClassFactory::class)
            ->syncWithExistsCode();

        $this->info('Finished!');

        return 0;
    }
}
