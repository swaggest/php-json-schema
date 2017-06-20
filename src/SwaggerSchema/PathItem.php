<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class PathItem extends ClassStructure {
	/** @var string */
	public $ref;

	/** @var Operation */
	public $get;

	/** @var Operation */
	public $put;

	/** @var Operation */
	public $post;

	/** @var Operation */
	public $delete;

	/** @var Operation */
	public $options;

	/** @var Operation */
	public $head;

	/** @var Operation */
	public $patch;

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array The parameters needed to send a valid API call. */
	public $parameters;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->ref = JsonBasicSchema::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->get = Operation::schema();
		$properties->put = Operation::schema();
		$properties->post = Operation::schema();
		$properties->delete = Operation::schema();
		$properties->options = Operation::schema();
		$properties->head = Operation::schema();
		$properties->patch = Operation::schema();
		$properties->parameters = JsonBasicSchema::arr();
		$properties->parameters->items = new JsonBasicSchema();
		$properties->parameters->items->oneOf[0] = new JsonBasicSchema();
		$properties->parameters->items->oneOf[0]->oneOf[0] = BodyParameter::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1] = JsonBasicSchema::object();
		$properties->parameters->items->oneOf[0]->oneOf[1]->oneOf[0] = HeaderParameterSubSchema::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1]->oneOf[1] = FormDataParameterSubSchema::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1]->oneOf[2] = QueryParameterSubSchema::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1]->oneOf[3] = PathParameterSubSchema::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1]->required = array (
		  0 => 'name',
		  1 => 'in',
		  2 => 'type',
		);
		$properties->parameters->items->oneOf[1] = JsonReference::schema();
		$properties->parameters->description = 'The parameters needed to send a valid API call.';
		$properties->parameters->uniqueItems = true;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @param string $ref
	 * @return $this
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
		return $this;
	}

	/**
	 * @param Operation $get
	 * @return $this
	 */
	public function setGet($get)
	{
		$this->get = $get;
		return $this;
	}

	/**
	 * @param Operation $put
	 * @return $this
	 */
	public function setPut($put)
	{
		$this->put = $put;
		return $this;
	}

	/**
	 * @param Operation $post
	 * @return $this
	 */
	public function setPost($post)
	{
		$this->post = $post;
		return $this;
	}

	/**
	 * @param Operation $delete
	 * @return $this
	 */
	public function setDelete($delete)
	{
		$this->delete = $delete;
		return $this;
	}

	/**
	 * @param Operation $options
	 * @return $this
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * @param Operation $head
	 * @return $this
	 */
	public function setHead($head)
	{
		$this->head = $head;
		return $this;
	}

	/**
	 * @param Operation $patch
	 * @return $this
	 */
	public function setPatch($patch)
	{
		$this->patch = $patch;
		return $this;
	}

	/**
	 * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array $parameters The parameters needed to send a valid API call.
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}
}

