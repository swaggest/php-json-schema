<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class FileSchema extends ClassStructure {
	/** @var string */
	public $format;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	public $default;

	/** @var string[]|array */
	public $required;

	/** @var string */
	public $type;

	/** @var bool */
	public $readOnly;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	public $example;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->format = JsonBasicSchema::string();
		$properties->title = JsonBasicSchema::string();
		$properties->description = JsonBasicSchema::string();
		$properties->default = new JsonBasicSchema();
		$properties->required = JsonBasicSchema::arr();
		$properties->required->items = JsonBasicSchema::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->type = JsonBasicSchema::string();
		$properties->type->enum = array (
		  0 => 'file',
		);
		$properties->readOnly = JsonBasicSchema::boolean();
		$properties->readOnly->default = false;
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new JsonBasicSchema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
		$ownerSchema->required = array (
		  0 => 'type',
		);
	}
}

