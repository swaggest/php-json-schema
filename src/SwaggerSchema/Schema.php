<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as Schema1;
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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->ref = Schema1::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->format = Schema1::string();
		$properties->title = Schema1::string();
		$properties->description = Schema1::string();
		$properties->default = new Schema1();
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$properties->maximum = Schema1::number();
		$properties->exclusiveMaximum = Schema1::boolean();
		$properties->exclusiveMaximum->default = false;
		$properties->minimum = Schema1::number();
		$properties->exclusiveMinimum = Schema1::boolean();
		$properties->exclusiveMinimum->default = false;
		$properties->maxLength = Schema1::integer();
		$properties->maxLength->minimum = 0;
		$properties->minLength = new Schema1();
		$properties->minLength->allOf[0] = Schema1::integer();
		$properties->minLength->allOf[0]->minimum = 0;
		$properties->minLength->allOf[1] = new Schema1();
		$properties->minLength->allOf[1]->default = 0;
		$properties->pattern = Schema1::string();
		$properties->pattern->format = 'regex';
		$properties->maxItems = Schema1::integer();
		$properties->maxItems->minimum = 0;
		$properties->minItems = new Schema1();
		$properties->minItems->allOf[0] = Schema1::integer();
		$properties->minItems->allOf[0]->minimum = 0;
		$properties->minItems->allOf[1] = new Schema1();
		$properties->minItems->allOf[1]->default = 0;
		$properties->uniqueItems = Schema1::boolean();
		$properties->uniqueItems->default = false;
		$properties->maxProperties = Schema1::integer();
		$properties->maxProperties->minimum = 0;
		$properties->minProperties = new Schema1();
		$properties->minProperties->allOf[0] = Schema1::integer();
		$properties->minProperties->allOf[0]->minimum = 0;
		$properties->minProperties->allOf[1] = new Schema1();
		$properties->minProperties->allOf[1]->default = 0;
		$properties->required = Schema1::arr();
		$properties->required->items = Schema1::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->additionalProperties = new Schema1();
		$properties->additionalProperties->anyOf[0] = Schema::schema();
		$properties->additionalProperties->anyOf[1] = Schema1::boolean();
		$properties->additionalProperties->default = new \stdClass();
		$properties->type = new Schema1();
		$properties->type->anyOf[0] = new Schema1();
		$properties->type->anyOf[0]->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1] = Schema1::arr();
		$properties->type->anyOf[1]->items = new Schema1();
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
		$properties->items = new Schema1();
		$properties->items->anyOf[0] = Schema::schema();
		$properties->items->anyOf[1] = Schema1::arr();
		$properties->items->anyOf[1]->items = Schema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->items->default = new \stdClass();
		$properties->allOf = Schema1::arr();
		$properties->allOf->items = Schema::schema();
		$properties->allOf->minItems = 1;
		$properties->properties = Schema1::object();
		$properties->properties->additionalProperties = Schema::schema();
		$properties->properties->default = new \stdClass();
		$properties->discriminator = Schema1::string();
		$properties->readOnly = Schema1::boolean();
		$properties->readOnly->default = false;
		$properties->xml = Xml::schema();
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new Schema1();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
	}
}

