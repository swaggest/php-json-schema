<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Oauth2AccessCodeSecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $flow;

	/** @var string[] */
	public $scopes;

	/** @var string */
	public $authorizationUrl;

	/** @var string */
	public $tokenUrl;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->type = JsonBasicSchema::string();
		$properties->type->enum = array (
		  0 => 'oauth2',
		);
		$properties->flow = JsonBasicSchema::string();
		$properties->flow->enum = array (
		  0 => 'accessCode',
		);
		$properties->scopes = JsonBasicSchema::object();
		$properties->scopes->additionalProperties = JsonBasicSchema::string();
		$properties->authorizationUrl = JsonBasicSchema::string();
		$properties->authorizationUrl->format = 'uri';
		$properties->tokenUrl = JsonBasicSchema::string();
		$properties->tokenUrl->format = 'uri';
		$properties->description = JsonBasicSchema::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'authorizationUrl',
		  3 => 'tokenUrl',
		);
	}

	/**
	 * @param string $type
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $flow
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setFlow($flow)
	{
		$this->flow = $flow;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string[] $scopes
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $authorizationUrl
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setAuthorizationUrl($authorizationUrl)
	{
		$this->authorizationUrl = $authorizationUrl;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $tokenUrl
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setTokenUrl($tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $description
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

