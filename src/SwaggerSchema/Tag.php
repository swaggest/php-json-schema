<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Tag extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $description;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->name = JsonBasicSchema::string();
		$properties->description = JsonBasicSchema::string();
		$properties->externalDocs = ExternalDocs::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		);
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

	/**
	 * @param ExternalDocs $externalDocs information about external documentation
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

