<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as Schema1;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Response extends ClassStructure {
	/** @var string */
	public $description;

	/** @var Schema|FileSchema */
	public $schema;

	/** @var Header[] */
	public $headers;

	public $examples;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->description = Schema1::string();
		$properties->schema = new Schema1();
		$properties->schema->oneOf[0] = Schema::schema();
		$properties->schema->oneOf[1] = FileSchema::schema();
		$properties->headers = Schema1::object();
		$properties->headers->additionalProperties = Header::schema();
		$properties->examples = Schema1::object();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'description',
		);
	}
}

