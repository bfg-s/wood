<?php

namespace Bfg\Wood\Generators;

use Bfg\Comcode\Comcode;
use Bfg\Comcode\Nodes\ClassMethodNode;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\Wood\Generators\RequestGenerator\RequestRuleGenerator;
use Bfg\Wood\Models\Request;
use Bfg\Wood\Models\Topic;
use ErrorException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

/**
 * @mixin Request
 */
class RequestGenerator extends GeneratorAbstract
{
    /**
     * @var array
     */
    protected array $prependChild = [
        RequestRuleGenerator::class
    ];

    /**
     * Collection of topics
     * @return Topic[]|Collection|array
     */
    protected function collection(): Collection|array
    {
        return Request::all();
    }

    protected function extends()
    {
        $this->class->extends(
            FormRequest::class
        );
    }

    /**
     * @return ClassMethodNode
     */
    protected function methodAuthorize(): ClassMethodNode
    {
        return $this->class
            ->publicMethod(['bool', 'authorize']);
    }

    /**
     * @param  ClassMethodNode  $node
     * @return ClassMethodNode
     */
    protected function methodAuthorizeComment(
        ClassMethodNode $node
    ): ClassMethodNode {
        if ($node->notExistsComment()) {
            $node->comment(
                fn(DocSubject $doc) => $doc->name('Determine if the user is authorized to make this request.')
                    ->tagReturn('bool')
            );
        }

        return $node;
    }

    /**
     * @param  ClassMethodNode  $node
     * @return void
     */
    protected function methodAuthorizeReturn(
        ClassMethodNode $node
    ): void {
        if ($node->notExistsReturn()) {
            $node->return()->real(true);
        }
    }

    /**
     * @return ClassMethodNode
     */
    protected function methodRules(): ClassMethodNode
    {
        return $this->class->publicMethod(['array', 'rules']);
    }

    /**
     * @param  ClassMethodNode  $node
     * @return ClassMethodNode
     */
    protected function methodRulesComment(
        ClassMethodNode $node
    ): ClassMethodNode {
        if ($node->notExistsComment()) {
            $node->comment(
                fn(DocSubject $doc) => $doc->name('Get the validation rules that apply to the request.')
                    ->tagReturn('array<string, mixed>')
            );
        }
        return $node;
    }

    /**
     * @param  ClassMethodNode  $node
     * @return void
     * @throws ErrorException
     */
    protected function methodRulesReturn(
        ClassMethodNode $node
    ): void {
        $rules = [];

        foreach ($this->rules as $rule) {
            foreach ($rule->rules as $rul) {
                $class = $rule->isClass($rul);
                $rules[$rule->name][] = $class
                    ? Comcode::useIfClass($class->class, $this->class)."::class"
                    : $rul;
                if ($class) {
                    $this->__makeRule($class);
                }
            }
        }

        $node->return()->real($rules);
    }

    protected function __makeRule(ClassSubject $subject)
    {
        $subject->publicMethod('__construct')->comment(
            fn (DocSubject $doc)
            => $doc->name('Create a new rule instance.')
                ->tagReturn('void')
        );
        $subject->publicMethod('passes')->comment(
            fn (DocSubject $doc)
            => $doc->name('Determine if the validation rule passes.')
                ->tagParam('string', 'attribute')
                ->tagParam('mixed', 'value')
                ->tagReturn('bool')
        )->expectParams('attribute', 'value');
        $subject->publicMethod('message')->comment(
            fn (DocSubject $doc)
            => $doc->name('Get the validation error message.')
                ->tagReturn('string')
        )->return()->real('The validation error message.');
    }
}
