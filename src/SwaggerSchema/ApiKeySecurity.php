<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
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
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->type = Schema::string();
		$properties->type->enum = array (
		  0 => 'apiKey',
		);
		$properties->name = Schema::string();
		$properties->in = Schema::string();
		$properties->in->enum = array (
		  0 => 'header',
		  1 => 'query',
		);
		$properties->description = Schema::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'name',
		  2 => 'in',
		);
	}
}

