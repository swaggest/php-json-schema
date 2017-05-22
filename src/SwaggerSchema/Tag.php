<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as BasicSchema;
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
	 * @param BasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, BasicSchema $ownerSchema)
	{
		$properties->name = BasicSchema::string();
		$properties->description = BasicSchema::string();
		$properties->externalDocs = ExternalDocs::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new BasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		);
	}
}

