<?php

namespace Bfg\Wood\Generators\ModelGenerator;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\InlineTrap;
use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Nodes\ClosureNode;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\Generators\GeneratorAbstract;
use Bfg\Wood\Generators\ModelGenerator;
use Bfg\Wood\Models\Model;
use Bfg\Wood\Models\ModelRelation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PhpParser\Node\Expr;

/**
 * @property-read ModelGenerator $parent
 * @mixin Model
 */
class ModelMigrationGenerator extends GeneratorAbstract
{
    /**
     * Collection of topics
     * @return Model[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Model::all();
    }

    protected function extends()
    {
        if ($this->migration) {
            $this->migration_class
                ->extends(Migration::class);
        }
    }

    protected function up()
    {
        if ($this->migration) {
            $this->migration_class
                ?->publicMethod('up')->clear()
                ->and(
                    fn(ClassMethodNode $node) => $node->line()->staticCall(Schema::class, 'create', $this->table(),
                        function (ClosureNode $node) {
                            $node->expectParams(['table', null, Blueprint::class]);
                            if ($this->increment || is_null($this->increment)) {
                                $node->line()->var('table')->func('id');
                            }
                            $field = null;
                            $morphs = [];
                            foreach ($this->fields as $field) {
                                if ($field->offsetExists('morphed')) {
                                    $fieldName = $field->offsetGet('morphed');
                                    if (!isset($morphs[$fieldName])) {
                                        $node->line()->var('table')
                                            ->func(
                                                $field->nullable ? 'nullableMorphs' : 'morphs',
                                                $fieldName,
                                            );
                                        $morphs[$fieldName] = true;
                                    }
                                    continue;
                                }

                                $node->line()->var('table')
                                    ->func($field->type, $field->name, ...($field->type_parameters ?? []))
                                    ->when($field->nullable, fn(InlineTrap $trap) => $trap->func('nullable'))
                                    ->when($field->has_default,
                                        fn(InlineTrap $trap) => $trap->func('default',
                                            $field->default === 'null' ? '' : $field->default))
                                    ->when($field->comment,
                                        fn(InlineTrap $trap) => $trap->func('comment', $field->comment))
                                    ->when($field->unique, fn(InlineTrap $trap) => $trap->func('unique'))
                                    ->when($field->unsigned, fn(InlineTrap $trap) => $trap->func('unsigned'))
                                    ->when($field->index, fn(InlineTrap $trap) => $trap->func('index'))
                                    ->when($field->type_details,
                                        fn(InlineTrap $trap) => collect($field->type_details)->map(
                                            fn($params, $name) => $trap->func($name, ...
                                                (is_array($params) ? $params : [$params]))
                                        )
                                    );
                            }

                            $foreigns = $this->relations()
                                ->where('type', '!=', 'belongsToMany')
                                ->where('type', '!=', 'morphTo')
                                ->where('type', '!=', 'morphOne')
                                ->where('type', '!=', 'morphMany')
                                ->where('type', '!=', 'morphToMany')
                                ->where('type', '!=', 'morphedByMany')
                                ->get();

                            $foreign = null;
                            /** @var ModelRelation $foreign */
                            foreach ($foreigns as $foreign) {
                                $node->line()->var('table')
                                    ->func('foreignId', $foreign->foreign_id)
                                    ->when($foreign->nullable, fn(InlineTrap $trap) => $trap->func('nullable'))
                                    ->func('constrained', $foreign->related_model->table())
                                    ->when($foreign->cascade_on_update,
                                        fn(InlineTrap $trap) => $trap->func('cascadeOnUpdate'))
                                    ->when($foreign->cascade_on_delete,
                                        fn(InlineTrap $trap) => $trap->func('cascadeOnDelete'))
                                    ->when($foreign->null_on_delete,
                                        fn(InlineTrap $trap) => $trap->func('nullOnDelete'));
                            }

                            $morphForeigns = $this->related()
                                ->whereIn('type', [
                                    'morphTo', 'morphOne', 'morphMany', 'morphToMany', 'morphedByMany'
                                ])
                                ->get()->unique('able');

                            /** @var ModelRelation $morphForeign */
                            foreach ($morphForeigns as $morphForeign) {
                                $node->line()->var('table')
                                    ->func($morphForeign->nullable ? 'nullableMorphs' : 'morphs', $morphForeign->able);
                            }

                            if (
                                $this->created
                                && $this->updated
                            ) {
                                $node->line()->var('table')->func('timestamps');
                            } else {
                                if (
                                    $this->created
                                    && !$this->updated
                                ) {
                                    $node->line()->var('table')
                                        ->func(
                                            'timestamp',
                                            'created_at'
                                        )->func('nullable');
                                } else {
                                    if (
                                        !$this->created
                                        && $this->updated
                                    ) {
                                        $node->line()->var('table')
                                            ->func(
                                                'timestamp',
                                                'updated_at'
                                            )->func('nullable');
                                    }
                                }
                            }

                            if ($this->deleted) {
                                $node->line()->var('table')
                                    ->func('softDeletes');
                            }
                        })
                )->comment(
                    fn(DocSubject $doc) => $doc->name('Run the migrations.')
                        ->tagReturn('void')
                );
        }
    }

    protected function down()
    {
        if ($this->migration) {
            $this->migration_class
                ?->publicMethod('down')->clear()
                ->and(
                    fn(ClassMethodNode $node) => $node->line()->staticCall(Schema::class, 'dropIfExists', $this->table)
                )->comment(
                    fn(DocSubject $doc) => $doc->name('Reverse the migrations.')
                        ->tagReturn('void')
                );
        }
    }
}
