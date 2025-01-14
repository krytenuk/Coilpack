<?php

namespace Expressionengine\Coilpack\Fieldtypes;

use Expressionengine\Coilpack\Contracts\Field;
use Expressionengine\Coilpack\FieldtypeOutput;
use Expressionengine\Coilpack\Models\FieldContent;
use Illuminate\Support\Collection;

abstract class Fieldtype
{
    public $name;

    public $id;

    private $modifiers;

    public function __construct(string $name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Apply the fieldtype to the data in provided
     *
     * @return FieldtypeOutput|null
     */
    abstract public function apply(FieldContent $content, array $parameters = []);

    public function withSettings($settings)
    {
        return $this;
    }

    /**
     * A list of supported parameters for the fieldtype
     */
    public function parametersForField(Field $field = null): array
    {
        return [];
    }

    /**
     * Retrieve a collection of Modifiers for this Fieldtype
     *
     * @return Illuminate\Support\Collection
     */
    final public function modifiers(): Collection
    {
        if (is_null($this->modifiers)) {
            $modifiers = $this->defineModifiers();
            $this->modifiers = collect($modifiers)->keyBy('name');
        }

        return clone $this->modifiers;
    }

    /**
     * Setup the list of Modifiers for this Fieldtype
     *
     * @return Modifier[]
     */
    public function defineModifiers(): array
    {
        return [];
    }

    /**
     * Determine whether the given modifier exists on this fieldtype
     *
     * @param  string  $name
     * @return bool
     */
    public function hasModifier($name)
    {
        return $this->modifiers()->has($name);
    }

    /**
     * Call a modifier on the given field content
     *
     * @return FieldtypeOutput
     */
    public function callModifier(FieldtypeOutput $content, string $name, array $parameters = [])
    {
        if ($this->hasModifier($name) && $this->modifiers()->get($name) instanceof Modifier) {
            return $this->modifiers()->get($name)->handle($content, $parameters);
        }
    }

    /**
     * The GraphQL Type that represents this Fieldtype
     *
     * @return Rebing\GraphQL\Support\Type|string
     */
    public function graphType()
    {
        return \GraphQL\Type\Definition\Type::string();
    }
}
