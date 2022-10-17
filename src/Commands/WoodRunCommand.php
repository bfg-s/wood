<?php

namespace Bfg\Wood\Commands;

use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Wood\ClassFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

class WoodRunCommand extends BaseWoodCommand
{
    /**
     * @var string
     */
    protected $signature = "wood:run";

    /**
     * @var string
     */
    protected $description = "Run wood generate";

    /**
     * @throws BindingResolutionException
     */
    public function handle(): int
    {
        app(ClassFactory::class)
            ->generate()
            ->save(
                fn (ClassSubject $subject)
                => $this->info("Saved: " . str_replace(base_path(), '', $subject->fileSubject->file))
            );

        $this->info('Finished!');

        return 0;
    }
}
