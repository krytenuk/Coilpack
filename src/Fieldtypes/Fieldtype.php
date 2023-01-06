<?php

namespace Expressionengine\Coilpack\Fieldtypes;

use Expressionengine\Coilpack\FieldtypeOutput;
use Expressionengine\Coilpack\Models\FieldContent;

abstract class Fieldtype
{
    public function __construct(string $name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Apply the fieldtype to the data in provided
     *
     * @param  FieldContent  $content
     * @return FieldtypeOutput
     */
    abstract public function apply(FieldContent $content, array $parameters = []);

    public function modifiers()
    {
        return [];
    }

    public function callModifier(FieldContent $content, string $name, array $parameters = [])
    {
        if (array_key_exists($name, $this->modifiers()) && $this->modifiers()[$name] instanceof Modifier) {
            return $this->modifiers()[$name]->handle($content, $parameters);
        }
    }

    public function graphType()
    {
        return \GraphQL\Type\Definition\Type::string();
    }
}