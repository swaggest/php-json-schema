<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
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
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->name = JsonBasicSchema::string();
		$properties->name->description = 'The identifying name of the contact person/organization.';
		$properties->url = JsonBasicSchema::string();
		$properties->url->description = 'The URL pointing to the contact information.';
		$properties->url->format = 'uri';
		$properties->email = JsonBasicSchema::string();
		$properties->email->description = 'The email address of the contact person/organization.';
		$properties->email->format = 'email';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'Contact information for the owners of the API.';
	}

	/**
	 * @param string $name The identifying name of the contact person/organization.
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $url The URL pointing to the contact information.
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @param string $email The email address of the contact person/organization.
	 * @return $this
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}
}

