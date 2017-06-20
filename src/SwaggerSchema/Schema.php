<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Schema extends ClassStructure {
	/** @var string */
	public $ref;

	/** @var string */
	public $format;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	public $default;

	/** @var float */
	public $multipleOf;

	/** @var float */
	public $maximum;

	/** @var bool */
	public $exclusiveMaximum;

	/** @var float */
	public $minimum;

	/** @var bool */
	public $exclusiveMinimum;

	/** @var int */
	public $maxLength;

	/** @var int */
	public $minLength;

	/** @var string */
	public $pattern;

	/** @var int */
	public $maxItems;

	/** @var int */
	public $minItems;

	/** @var bool */
	public $uniqueItems;

	/** @var int */
	public $maxProperties;

	/** @var int */
	public $minProperties;

	/** @var string[]|array */
	public $required;

	/** @var array */
	public $enum;

	/** @var Schema|bool */
	public $additionalProperties;

	/** @var array */
	public $type;

	/** @var Schema|Schema[]|array */
	public $items;

	/** @var Schema[]|array */
	public $allOf;

	/** @var Schema[] */
	public $properties;

	/** @var string */
	public $discriminator;

	/** @var bool */
	public $readOnly;

	/** @var Xml */
	public $xml;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	public $example;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->ref = JsonBasicSchema::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->format = JsonBasicSchema::string();
		$properties->title = JsonBasicSchema::string();
		$properties->description = JsonBasicSchema::string();
		$properties->default = new JsonBasicSchema();
		$properties->multipleOf = JsonBasicSchema::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$properties->maximum = JsonBasicSchema::number();
		$properties->exclusiveMaximum = JsonBasicSchema::boolean();
		$properties->exclusiveMaximum->default = false;
		$properties->minimum = JsonBasicSchema::number();
		$properties->exclusiveMinimum = JsonBasicSchema::boolean();
		$properties->exclusiveMinimum->default = false;
		$properties->maxLength = JsonBasicSchema::integer();
		$properties->maxLength->minimum = 0;
		$properties->minLength = new JsonBasicSchema();
		$properties->minLength->allOf[0] = JsonBasicSchema::integer();
		$properties->minLength->allOf[0]->minimum = 0;
		$properties->minLength->allOf[1] = new JsonBasicSchema();
		$properties->minLength->allOf[1]->default = 0;
		$properties->pattern = JsonBasicSchema::string();
		$properties->pattern->format = 'regex';
		$properties->maxItems = JsonBasicSchema::integer();
		$properties->maxItems->minimum = 0;
		$properties->minItems = new JsonBasicSchema();
		$properties->minItems->allOf[0] = JsonBasicSchema::integer();
		$properties->minItems->allOf[0]->minimum = 0;
		$properties->minItems->allOf[1] = new JsonBasicSchema();
		$properties->minItems->allOf[1]->default = 0;
		$properties->uniqueItems = JsonBasicSchema::boolean();
		$properties->uniqueItems->default = false;
		$properties->maxProperties = JsonBasicSchema::integer();
		$properties->maxProperties->minimum = 0;
		$properties->minProperties = new JsonBasicSchema();
		$properties->minProperties->allOf[0] = JsonBasicSchema::integer();
		$properties->minProperties->allOf[0]->minimum = 0;
		$properties->minProperties->allOf[1] = new JsonBasicSchema();
		$properties->minProperties->allOf[1]->default = 0;
		$properties->required = JsonBasicSchema::arr();
		$properties->required->items = JsonBasicSchema::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->enum = JsonBasicSchema::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->additionalProperties = new JsonBasicSchema();
		$properties->additionalProperties->anyOf[0] = Schema::schema();
		$properties->additionalProperties->anyOf[1] = JsonBasicSchema::boolean();
		$properties->additionalProperties->default = (object)array (
		);
		$properties->type = new JsonBasicSchema();
		$properties->type->anyOf[0] = new JsonBasicSchema();
		$properties->type->anyOf[0]->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1] = JsonBasicSchema::arr();
		$properties->type->anyOf[1]->items = new JsonBasicSchema();
		$properties->type->anyOf[1]->items->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1]->minItems = 1;
		$properties->type->anyOf[1]->uniqueItems = true;
		$properties->items = new JsonBasicSchema();
		$properties->items->anyOf[0] = Schema::schema();
		$properties->items->anyOf[1] = JsonBasicSchema::arr();
		$properties->items->anyOf[1]->items = Schema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->items->default = (object)array (
		);
		$properties->allOf = JsonBasicSchema::arr();
		$properties->allOf->items = Schema::schema();
		$properties->allOf->minItems = 1;
		$properties->properties = JsonBasicSchema::object();
		$properties->properties->additionalProperties = Schema::schema();
		$properties->properties->default = (object)array (
		);
		$properties->discriminator = JsonBasicSchema::string();
		$properties->readOnly = JsonBasicSchema::boolean();
		$properties->readOnly->default = false;
		$properties->xml = Xml::schema();
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new JsonBasicSchema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
	}

	/**
	 * @param string $ref
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $format
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $title
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setTitle($title)
	{
		$this->title = $title;
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

	/**
	 * @param $default
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param float $multipleOf
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMultipleOf($multipleOf)
	{
		$this->multipleOf = $multipleOf;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param float $maximum
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMaximum($maximum)
	{
		$this->maximum = $maximum;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $exclusiveMaximum
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExclusiveMaximum($exclusiveMaximum)
	{
		$this->exclusiveMaximum = $exclusiveMaximum;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param float $minimum
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMinimum($minimum)
	{
		$this->minimum = $minimum;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $exclusiveMinimum
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExclusiveMinimum($exclusiveMinimum)
	{
		$this->exclusiveMinimum = $exclusiveMinimum;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $maxLength
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMaxLength($maxLength)
	{
		$this->maxLength = $maxLength;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $minLength
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMinLength($minLength)
	{
		$this->minLength = $minLength;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $pattern
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $maxItems
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMaxItems($maxItems)
	{
		$this->maxItems = $maxItems;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $minItems
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMinItems($minItems)
	{
		$this->minItems = $minItems;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $uniqueItems
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setUniqueItems($uniqueItems)
	{
		$this->uniqueItems = $uniqueItems;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $maxProperties
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMaxProperties($maxProperties)
	{
		$this->maxProperties = $maxProperties;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param int $minProperties
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setMinProperties($minProperties)
	{
		$this->minProperties = $minProperties;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string[]|array $required
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param array $enum
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setEnum($enum)
	{
		$this->enum = $enum;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param Schema|bool $additionalProperties
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setAdditionalProperties($additionalProperties)
	{
		$this->additionalProperties = $additionalProperties;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param array $type
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
	 * @param Schema|Schema[]|array $items
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param Schema[]|array $allOf
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setAllOf($allOf)
	{
		$this->allOf = $allOf;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param Schema[] $properties
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setProperties($properties)
	{
		$this->properties = $properties;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param string $discriminator
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setDiscriminator($discriminator)
	{
		$this->discriminator = $discriminator;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param bool $readOnly
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setReadOnly($readOnly)
	{
		$this->readOnly = $readOnly;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param Xml $xml
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setXml($xml)
	{
		$this->xml = $xml;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param ExternalDocs $externalDocs information about external documentation
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */

	/**
	 * @param $example
	 * @return $this
	 * @codeCoverageIgnoreStart 
	 */
	public function setExample($example)
	{
		$this->example = $example;
		return $this;
	}
	/**
	 * @codeCoverageIgnoreEnd 
	 */
}

