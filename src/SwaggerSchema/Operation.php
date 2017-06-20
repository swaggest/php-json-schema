<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class Operation extends ClassStructure {
	/** @var string[]|array */
	public $tags;

	/** @var string A brief summary of the operation. */
	public $summary;

	/** @var string A longer description of the operation, GitHub Flavored Markdown is allowed. */
	public $description;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	/** @var string A unique identifier of the operation. */
	public $operationId;

	/** @var string[]|array A list of MIME types the API can produce. */
	public $produces;

	/** @var string[]|array A list of MIME types the API can consume. */
	public $consumes;

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array The parameters needed to send a valid API call. */
	public $parameters;

	/** @var Response[]|JsonReference[] Response objects names can either be any valid HTTP status code or 'default'. */
	public $responses;

	/** @var string[]|array The transfer protocol of the API. */
	public $schemes;

	/** @var bool */
	public $deprecated;

	/** @var string[][]|array[][]|array */
	public $security;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->tags = JsonBasicSchema::arr();
		$properties->tags->items = JsonBasicSchema::string();
		$properties->tags->uniqueItems = true;
		$properties->summary = JsonBasicSchema::string();
		$properties->summary->description = 'A brief summary of the operation.';
		$properties->description = JsonBasicSchema::string();
		$properties->description->description = 'A longer description of the operation, GitHub Flavored Markdown is allowed.';
		$properties->externalDocs = ExternalDocs::schema();
		$properties->operationId = JsonBasicSchema::string();
		$properties->operationId->description = 'A unique identifier of the operation.';
		$properties->produces = new JsonBasicSchema();
		$properties->produces->allOf[0] = JsonBasicSchema::arr();
		$properties->produces->allOf[0]->items = JsonBasicSchema::string();
		$properties->produces->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->produces->allOf[0]->uniqueItems = true;
		$properties->produces->description = 'A list of MIME types the API can produce.';
		$properties->consumes = new JsonBasicSchema();
		$properties->consumes->allOf[0] = JsonBasicSchema::arr();
		$properties->consumes->allOf[0]->items = JsonBasicSchema::string();
		$properties->consumes->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->consumes->allOf[0]->uniqueItems = true;
		$properties->consumes->description = 'A list of MIME types the API can consume.';
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
		$properties->responses = JsonBasicSchema::object();
		$properties->responses->additionalProperties = false;
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$'] = new JsonBasicSchema();
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$']->oneOf[0] = Response::schema();
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$']->oneOf[1] = JsonReference::schema();
		$properties->responses->patternProperties['^x-'] = new JsonBasicSchema();
		$properties->responses->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->responses->not = JsonBasicSchema::object();
		$properties->responses->not->additionalProperties = false;
		$properties->responses->not->patternProperties['^x-'] = new JsonBasicSchema();
		$properties->responses->not->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->responses->description = 'Response objects names can either be any valid HTTP status code or \'default\'.';
		$properties->responses->minProperties = 1;
		$properties->schemes = JsonBasicSchema::arr();
		$properties->schemes->items = JsonBasicSchema::string();
		$properties->schemes->items->enum = array (
		  0 => 'http',
		  1 => 'https',
		  2 => 'ws',
		  3 => 'wss',
		);
		$properties->schemes->description = 'The transfer protocol of the API.';
		$properties->schemes->uniqueItems = true;
		$properties->deprecated = JsonBasicSchema::boolean();
		$properties->deprecated->default = false;
		$properties->security = JsonBasicSchema::arr();
		$properties->security->items = JsonBasicSchema::object();
		$properties->security->items->additionalProperties = JsonBasicSchema::arr();
		$properties->security->items->additionalProperties->items = JsonBasicSchema::string();
		$properties->security->items->additionalProperties->uniqueItems = true;
		$properties->security->uniqueItems = true;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'responses',
		);
	}
}

