<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class ApiKeySecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $name;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->type = JsonBasicSchema::string();
		$properties->type->enum = array (
		  0 => 'apiKey',
		);
		$properties->name = JsonBasicSchema::string();
		$properties->in = JsonBasicSchema::string();
		$properties->in->enum = array (
		  0 => 'header',
		  1 => 'query',
		);
		$properties->description = JsonBasicSchema::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'name',
		  2 => 'in',
		);
	}

	/**
	 * @param string $type
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

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
	 * @param string $in
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $description
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

