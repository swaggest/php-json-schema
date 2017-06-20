<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class FormDataParameterSubSchema extends ClassStructure {
	/** @var bool Determines whether or not this parameter is required or optional. */
	public $required;

	/** @var string Determines the location of the parameter. */
	public $in;

	/** @var string A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed. */
	public $description;

	/** @var string The name of the parameter. */
	public $name;

	/** @var bool allows sending a parameter by name only or with an empty value. */
	public $allowEmptyValue;

	/** @var string */
	public $type;

	/** @var string */
	public $format;

	/** @var PrimitivesItems */
	public $items;

	/** @var string */
	public $collectionFormat;

	public $default;

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

	/** @var array */
	public $enum;

	/** @var float */
	public $multipleOf;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->required = JsonBasicSchema::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->in = JsonBasicSchema::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'formData',
		);
		$properties->description = JsonBasicSchema::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = JsonBasicSchema::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->allowEmptyValue = JsonBasicSchema::boolean();
		$properties->allowEmptyValue->description = 'allows sending a parameter by name only or with an empty value.';
		$properties->allowEmptyValue->default = false;
		$properties->type = JsonBasicSchema::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'boolean',
		  3 => 'integer',
		  4 => 'array',
		  5 => 'file',
		);
		$properties->format = JsonBasicSchema::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = JsonBasicSchema::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		  4 => 'multi',
		);
		$properties->default = new JsonBasicSchema();
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
		$properties->enum = JsonBasicSchema::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = JsonBasicSchema::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema = new JsonBasicSchema();
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @param bool $required Determines whether or not this parameter is required or optional.
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @param string $in Determines the location of the parameter.
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @param string $description A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @param string $name The name of the parameter.
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param bool $allowEmptyValue allows sending a parameter by name only or with an empty value.
	 * @return $this
	 */
	public function setAllowEmptyValue($allowEmptyValue)
	{
		$this->allowEmptyValue = $allowEmptyValue;
		return $this;
	}

	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @param string $format
	 * @return $this
	 */
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * @param PrimitivesItems $items
	 * @return $this
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * @param string $collectionFormat
	 * @return $this
	 */
	public function setCollectionFormat($collectionFormat)
	{
		$this->collectionFormat = $collectionFormat;
		return $this;
	}

	/**
	 * @param $default
	 * @return $this
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		return $this;
	}

	/**
	 * @param float $maximum
	 * @return $this
	 */
	public function setMaximum($maximum)
	{
		$this->maximum = $maximum;
		return $this;
	}

	/**
	 * @param bool $exclusiveMaximum
	 * @return $this
	 */
	public function setExclusiveMaximum($exclusiveMaximum)
	{
		$this->exclusiveMaximum = $exclusiveMaximum;
		return $this;
	}

	/**
	 * @param float $minimum
	 * @return $this
	 */
	public function setMinimum($minimum)
	{
		$this->minimum = $minimum;
		return $this;
	}

	/**
	 * @param bool $exclusiveMinimum
	 * @return $this
	 */
	public function setExclusiveMinimum($exclusiveMinimum)
	{
		$this->exclusiveMinimum = $exclusiveMinimum;
		return $this;
	}

	/**
	 * @param int $maxLength
	 * @return $this
	 */
	public function setMaxLength($maxLength)
	{
		$this->maxLength = $maxLength;
		return $this;
	}

	/**
	 * @param int $minLength
	 * @return $this
	 */
	public function setMinLength($minLength)
	{
		$this->minLength = $minLength;
		return $this;
	}

	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function setPattern($pattern)
	{
		$this->pattern = $pattern;
		return $this;
	}

	/**
	 * @param int $maxItems
	 * @return $this
	 */
	public function setMaxItems($maxItems)
	{
		$this->maxItems = $maxItems;
		return $this;
	}

	/**
	 * @param int $minItems
	 * @return $this
	 */
	public function setMinItems($minItems)
	{
		$this->minItems = $minItems;
		return $this;
	}

	/**
	 * @param bool $uniqueItems
	 * @return $this
	 */
	public function setUniqueItems($uniqueItems)
	{
		$this->uniqueItems = $uniqueItems;
		return $this;
	}

	/**
	 * @param array $enum
	 * @return $this
	 */
	public function setEnum($enum)
	{
		$this->enum = $enum;
		return $this;
	}

	/**
	 * @param float $multipleOf
	 * @return $this
	 */
	public function setMultipleOf($multipleOf)
	{
		$this->multipleOf = $multipleOf;
		return $this;
	}
}

