<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
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
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->name = Schema::string();
		$properties->namespace = Schema::string();
		$properties->prefix = Schema::string();
		$properties->attribute = Schema::boolean();
		$properties->attribute->default = false;
		$properties->wrapped = Schema::boolean();
		$properties->wrapped->default = false;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}
}

