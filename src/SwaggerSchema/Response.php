<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Response extends ClassStructure {
	/** @var string */
	public $description;

	/** @var Schema|FileSchema */
	public $schema;

	/** @var Header[] */
	public $headers;

	public $examples;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->description = JsonBasicSchema::string();
		$properties->schema = new JsonBasicSchema();
		$properties->schema->oneOf[0] = Schema::schema();
		$properties->schema->oneOf[1] = FileSchema::schema();
		$properties->headers = JsonBasicSchema::object();
		$properties->headers->additionalProperties = Header::schema();
		$properties->examples = JsonBasicSchema::object();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'description',
		);
	}

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

	/**
	 * @param Schema|FileSchema $schema
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setSchema($schema)
	{
		$this->schema = $schema;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param Header[] $headers
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param $examples
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExamples($examples)
	{
		$this->examples = $examples;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

