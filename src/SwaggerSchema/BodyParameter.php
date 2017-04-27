<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as Schema1;
use Swaggest\JsonSchema\Structure\ClassStructure;


class BodyParameter extends ClassStructure {
	/** @var string A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed. */
	public $description;

	/** @var string The name of the parameter. */
	public $name;

	/** @var string Determines the location of the parameter. */
	public $in;

	/** @var bool Determines whether or not this parameter is required or optional. */
	public $required;

	/** @var Schema A deterministic version of a JSON Schema object. */
	public $schema;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'body',
		);
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->schema = Schema::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		  1 => 'in',
		  2 => 'schema',
		);
	}
}

