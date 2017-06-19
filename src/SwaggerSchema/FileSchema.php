<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
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
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->format = Schema::string();
		$properties->title = Schema::string();
		$properties->description = Schema::string();
		$properties->default = new Schema();
		$properties->required = Schema::arr();
		$properties->required->items = Schema::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->type = Schema::string();
		$properties->type->enum = array (
		  0 => 'file',
		);
		$properties->readOnly = Schema::boolean();
		$properties->readOnly->default = false;
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new Schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
		$ownerSchema->required = array (
		  0 => 'type',
		);
	}
}

