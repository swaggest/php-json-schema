<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Xml extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $namespace;

	/** @var string */
	public $prefix;

	/** @var bool */
	public $attribute;

	/** @var bool */
	public $wrapped;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->name = JsonBasicSchema::string();
		$properties->namespace = JsonBasicSchema::string();
		$properties->prefix = JsonBasicSchema::string();
		$properties->attribute = JsonBasicSchema::boolean();
		$properties->attribute->default = false;
		$properties->wrapped = JsonBasicSchema::boolean();
		$properties->wrapped->default = false;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @param string $name
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $namespace
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $prefix
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $attribute
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setAttribute($attribute)
	{
		$this->attribute = $attribute;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $wrapped
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setWrapped($wrapped)
	{
		$this->wrapped = $wrapped;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

