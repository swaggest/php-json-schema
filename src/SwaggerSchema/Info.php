<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Info extends ClassStructure {
	/** @var string A unique and precise title of the API. */
	public $title;

	/** @var string A semantic version number of the API. */
	public $version;

	/** @var string A longer description of the API. Should be different from the title.  GitHub Flavored Markdown is allowed. */
	public $description;

	/** @var string The terms of service for the API. */
	public $termsOfService;

	/** @var Contact Contact information for the owners of the API. */
	public $contact;

	/** @var License */
	public $license;

	/**
	 * @param Properties|static $properties
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->title = Schema::string();
		$properties->title->description = 'A unique and precise title of the API.';
		$properties->version = Schema::string();
		$properties->version->description = 'A semantic version number of the API.';
		$properties->description = Schema::string();
		$properties->description->description = 'A longer description of the API. Should be different from the title.  GitHub Flavored Markdown is allowed.';
		$properties->termsOfService = Schema::string();
		$properties->termsOfService->description = 'The terms of service for the API.';
		$properties->contact = Contact::schema();
		$properties->license = License::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'General information about the API.';
		$ownerSchema->required = array (
		  0 => 'version',
		  1 => 'title',
		);
	}
}

