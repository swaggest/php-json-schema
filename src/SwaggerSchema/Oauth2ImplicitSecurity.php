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
 * Built from #/definitions/oauth2ImplicitSecurity
 */
class Oauth2ImplicitSecurity extends ClassStructure {
	const OAUTH2 = 'oauth2';

	const IMPLICIT = 'implicit';

	/** @var string */
	public $type;

	/** @var string */
	public $flow;

	/** @var string[] */
	public $scopes;

	/** @var string */
	public $authorizationUrl;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->type = JsonBasicSchema::string();
		$properties->type->enum = array(
		    self::OAUTH2,
		);
		$properties->flow = JsonBasicSchema::string();
		$properties->flow->enum = array(
		    self::IMPLICIT,
		);
		$properties->scopes = JsonBasicSchema::object();
		$properties->scopes->additionalProperties = JsonBasicSchema::string();
		$properties->authorizationUrl = JsonBasicSchema::string();
		$properties->authorizationUrl->format = 'uri';
		$properties->description = JsonBasicSchema::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'authorizationUrl',
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
	/** @codeCoverageIgnoreEnd */

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
	/** @codeCoverageIgnoreEnd */

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
	/** @codeCoverageIgnoreEnd */

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
	/** @codeCoverageIgnoreEnd */

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
	/** @codeCoverageIgnoreEnd */
}

