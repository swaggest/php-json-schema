<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


/**
 * General information about the API.
 * Built from #/definitions/info
 */
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
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->title = JsonBasicSchema::string();
		$properties->title->description = 'A unique and precise title of the API.';
		$properties->version = JsonBasicSchema::string();
		$properties->version->description = 'A semantic version number of the API.';
		$properties->description = JsonBasicSchema::string();
		$properties->description->description = 'A longer description of the API. Should be different from the title.  GitHub Flavored Markdown is allowed.';
		$properties->termsOfService = JsonBasicSchema::string();
		$properties->termsOfService->description = 'The terms of service for the API.';
		$properties->contact = Contact::schema();
		$properties->license = License::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'General information about the API.';
		$ownerSchema->required = array (
		  0 => 'version',
		  1 => 'title',
		);
	}

	/**
	 * @param string $title A unique and precise title of the API.
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */

	/**
	 * @param string $version A semantic version number of the API.
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setVersion($version)
	{
		$this->version = $version;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */

	/**
	 * @param string $description A longer description of the API. Should be different from the title.  GitHub Flavored Markdown is allowed.
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */

	/**
	 * @param string $termsOfService The terms of service for the API.
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setTermsOfService($termsOfService)
	{
		$this->termsOfService = $termsOfService;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */

	/**
	 * @param Contact $contact Contact information for the owners of the API.
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setContact($contact)
	{
		$this->contact = $contact;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */

	/**
	 * @param License $license
	 * @return $this
	 * @codeCoverageIgnoreStart
	 */
	public function setLicense($license)
	{
		$this->license = $license;
		return $this;
	}
	/** @codeCoverageIgnoreEnd */
}

