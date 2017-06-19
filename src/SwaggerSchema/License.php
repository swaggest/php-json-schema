<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class License extends ClassStructure {
	/** @var string The name of the license type. It's encouraged to use an OSI compatible license. */
	public $name;

	/** @var string The URL pointing to the license. */
	public $url;

	/**
	 * @param Properties|static $properties
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->name = Schema::string();
		$properties->name->description = 'The name of the license type. It\'s encouraged to use an OSI compatible license.';
		$properties->url = Schema::string();
		$properties->url->description = 'The URL pointing to the license.';
		$properties->url->format = 'uri';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		);
	}
}

