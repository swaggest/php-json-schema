<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Structure\ClassStructure;


class JsonSchema extends ClassStructure {
	/** @var string */
	public $id;

	/** @var string */
	public $schema;

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

	/** @var bool|JsonSchema */
	public $additionalItems;

	/** @var JsonSchema|JsonSchema[]|array */
	public $items;

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

	/** @var bool|JsonSchema */
	public $additionalProperties;

	/** @var JsonSchema[] */
	public $definitions;

	/** @var JsonSchema[] */
	public $properties;

	/** @var JsonSchema[] */
	public $patternProperties;

	/** @var JsonSchema[]|string[][]|array[] */
	public $dependencies;

	/** @var array */
	public $enum;

	/** @var array */
	public $type;

	/** @var string */
	public $format;

	/** @var string */
	public $ref;

	/** @var JsonSchema[]|array */
	public $allOf;

	/** @var JsonSchema[]|array */
	public $anyOf;

	/** @var JsonSchema[]|array */
	public $oneOf;

	/** @var JsonSchema Core schema meta-schema */
	public $not;

	/**
	 * @param Properties|static $properties
	 * @param Schema $ownerSchema
	 */
	public static function setUpProperties($properties, Schema $ownerSchema)
	{
		$properties->id = Schema::string();
		$properties->id->format = 'uri';
		$properties->schema = Schema::string();
		$properties->schema->format = 'uri';
		$ownerSchema->addPropertyMapping('$schema', self::names()->schema);
		$properties->title = Schema::string();
		$properties->description = Schema::string();
		$properties->default = new Schema();
		$properties->multipleOf = Schema::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$properties->maximum = Schema::number();
		$properties->exclusiveMaximum = Schema::boolean();
		$properties->exclusiveMaximum->default = false;
		$properties->minimum = Schema::number();
		$properties->exclusiveMinimum = Schema::boolean();
		$properties->exclusiveMinimum->default = false;
		$properties->maxLength = Schema::integer();
		$properties->maxLength->minimum = 0;
		$properties->minLength = new Schema();
		$properties->minLength->allOf[0] = Schema::integer();
		$properties->minLength->allOf[0]->minimum = 0;
		$properties->minLength->allOf[1] = new Schema();
		$properties->minLength->allOf[1]->default = 0;
		$properties->pattern = Schema::string();
		$properties->pattern->format = 'regex';
		$properties->additionalItems = new Schema();
		$properties->additionalItems->anyOf[0] = Schema::boolean();
		$properties->additionalItems->anyOf[1] = Schema::schema();
		$properties->additionalItems->default = (object)array (
		);
		$properties->items = new Schema();
		$properties->items->anyOf[0] = Schema::schema();
		$properties->items->anyOf[1] = Schema::arr();
		$properties->items->anyOf[1]->items = Schema::schema();
		$properties->items->anyOf[1]->minItems = 1;
		$properties->items->default = (object)array (
		);
		$properties->maxItems = Schema::integer();
		$properties->maxItems->minimum = 0;
		$properties->minItems = new Schema();
		$properties->minItems->allOf[0] = Schema::integer();
		$properties->minItems->allOf[0]->minimum = 0;
		$properties->minItems->allOf[1] = new Schema();
		$properties->minItems->allOf[1]->default = 0;
		$properties->uniqueItems = Schema::boolean();
		$properties->uniqueItems->default = false;
		$properties->maxProperties = Schema::integer();
		$properties->maxProperties->minimum = 0;
		$properties->minProperties = new Schema();
		$properties->minProperties->allOf[0] = Schema::integer();
		$properties->minProperties->allOf[0]->minimum = 0;
		$properties->minProperties->allOf[1] = new Schema();
		$properties->minProperties->allOf[1]->default = 0;
		$properties->required = Schema::arr();
		$properties->required->items = Schema::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->additionalProperties = new Schema();
		$properties->additionalProperties->anyOf[0] = Schema::boolean();
		$properties->additionalProperties->anyOf[1] = Schema::schema();
		$properties->additionalProperties->default = (object)array (
		);
		$properties->definitions = Schema::object();
		$properties->definitions->additionalProperties = Schema::schema();
		$properties->definitions->default = (object)array (
		);
		$properties->properties = Schema::object();
		$properties->properties->additionalProperties = Schema::schema();
		$properties->properties->default = (object)array (
		);
		$properties->patternProperties = Schema::object();
		$properties->patternProperties->additionalProperties = Schema::schema();
		$properties->patternProperties->default = (object)array (
		);
		$properties->dependencies = Schema::object();
		$properties->dependencies->additionalProperties = new Schema();
		$properties->dependencies->additionalProperties->anyOf[0] = Schema::schema();
		$properties->dependencies->additionalProperties->anyOf[1] = Schema::arr();
		$properties->dependencies->additionalProperties->anyOf[1]->items = Schema::string();
		$properties->dependencies->additionalProperties->anyOf[1]->minItems = 1;
		$properties->dependencies->additionalProperties->anyOf[1]->uniqueItems = true;
		$properties->enum = Schema::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->type = new Schema();
		$properties->type->anyOf[0] = new Schema();
		$properties->type->anyOf[0]->enum = array (
		  0 => 'array',
		  1 => 'boolean',
		  2 => 'integer',
		  3 => 'null',
		  4 => 'number',
		  5 => 'object',
		  6 => 'string',
		);
		$properties->type->anyOf[1] = Schema::arr();
		$properties->type->anyOf[1]->items = new Schema();
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
		$properties->format = Schema::string();
		$properties->ref = Schema::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->allOf = Schema::arr();
		$properties->allOf->items = Schema::schema();
		$properties->allOf->minItems = 1;
		$properties->anyOf = Schema::arr();
		$properties->anyOf->items = Schema::schema();
		$properties->anyOf->minItems = 1;
		$properties->oneOf = Schema::arr();
		$properties->oneOf->items = Schema::schema();
		$properties->oneOf->minItems = 1;
		$properties->not = Schema::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->id = 'http://json-schema.org/draft-04/schema#';
		$ownerSchema->schema = 'http://json-schema.org/draft-04/schema#';
		$ownerSchema->description = 'Core schema meta-schema';
		$ownerSchema->default = (object)array (
		);
		$ownerSchema->dependencies = (object)array (
		  'exclusiveMaximum' => 
		  array (
		    0 => 'maximum',
		  ),
		  'exclusiveMinimum' => 
		  array (
		    0 => 'minimum',
		  ),
		);
	}
}

