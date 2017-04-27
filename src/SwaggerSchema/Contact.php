<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Contact extends ClassStructure {
	/** @var string The identifying name of the contact person/organization. */
	public $name;

	/** @var string The URL pointing to the contact information. */
	public $url;

	/** @var string The email address of the contact person/organization. */
	public $email;

	/**
	 * @param Properties|static $properties
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->name = Schema::string();
		$properties->name->description = 'The identifying name of the contact person/organization.';
		$properties->url = Schema::string();
		$properties->url->description = 'The URL pointing to the contact information.';
		$properties->url->format = 'uri';
		$properties->email = Schema::string();
		$properties->email->description = 'The email address of the contact person/organization.';
		$properties->email->format = 'email';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'Contact information for the owners of the API.';
	}
}

