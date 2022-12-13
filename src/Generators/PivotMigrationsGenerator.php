<?php

namespace Bfg\Wood\Generators;

use Illuminate\Database\Migrations\Migration;
use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Nodes\ClosureNode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\Models\ModelRelation;
use Bfg\Wood\Models\Topic;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin ModelRelation
 */
class PivotMigrationsGenerator extends GeneratorAbstract
{

    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return ModelRelation::where('type', 'belongsToMany')
            ->get();
    }

    protected function extends()
    {
        $this->pivot_migration_class
            ->extends(Migration::class);
    }

    protected function up()
    {
        $model = $this->model()->first();
        $table = $model->table() . '_' . $this->name;

        $this->pivot_migration_class
            ?->publicMethod('up')->clear()
            ->and(
                fn(ClassMethodNode $node) => $node->line()->staticCall(Schema::class, 'create', $table,
                    function (ClosureNode $node) use ($model) {
                        $node->expectParams(['table', null, Blueprint::class]);
                        $related = $this->related_model()->first();
                        $node->line()->var('table')
                            ->func('foreignId', $model->foreign_id)
                            ->func('constrained', $model->table());
                        $node->line()->var('table')
                            ->func('foreignId', $related->foreign_id)
                            ->func('constrained', $related->table());
                    })
            )->comment(
                fn(DocSubject $doc) => $doc->name('Run the migrations.')
                    ->tagReturn('void')
            );
    }

    protected function down()
    {
        $model = $this->model()->first();
        $table = $model->table() . '_' . $this->name;

        $this->pivot_migration_class
            ?->publicMethod('down')->clear()
            ->and(
                fn(ClassMethodNode $node)
                => $node->line()
                    ->staticCall(Schema::class, 'dropIfExists', $table)
            )->comment(
                fn(DocSubject $doc) => $doc->name('Reverse the migrations.')
                    ->tagReturn('void')
            );
    }
}
