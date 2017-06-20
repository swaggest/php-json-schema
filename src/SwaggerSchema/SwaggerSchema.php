<?php
/**
 * @file ATTENTION!!! The code below was carefully crafted by a mean machine.
 * Please consider to NOT put any emotional human-generated modifications as the splendid AI will throw them away with no mercy.
 */

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as JsonBasicSchema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class SwaggerSchema extends ClassStructure {
	/** @var string The Swagger version of this document. */
	public $swagger;

	/** @var Info General information about the API. */
	public $info;

	/** @var string The host (name or ip) of the API. Example: 'swagger.io' */
	public $host;

	/** @var string The base path to the API. Example: '/api'. */
	public $basePath;

	/** @var string[]|array The transfer protocol of the API. */
	public $schemes;

	/** @var string[]|array A list of MIME types accepted by the API. */
	public $consumes;

	/** @var string[]|array A list of MIME types the API can produce. */
	public $produces;

	/** @var PathItem[] Relative paths to the individual endpoints. They must be relative to the 'basePath'. */
	public $paths;

	/** @var Schema[] One or more JSON objects describing the schemas being consumed and produced by the API. */
	public $definitions;

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[] One or more JSON representations for parameters */
	public $parameters;

	/** @var Response[] One or more JSON representations for parameters */
	public $responses;

	/** @var string[][]|array[][]|array */
	public $security;

	/** @var BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[] */
	public $securityDefinitions;

	/** @var Tag[]|array */
	public $tags;

	/** @var ExternalDocs information about external documentation */
	public $externalDocs;

	/**
	 * @param Properties|static $properties
	 * @param JsonBasicSchema $ownerSchema
	 */
	public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
	{
		$properties->swagger = JsonBasicSchema::string();
		$properties->swagger->description = 'The Swagger version of this document.';
		$properties->swagger->enum = array (
		  0 => '2.0',
		);
		$properties->info = Info::schema();
		$properties->host = JsonBasicSchema::string();
		$properties->host->description = 'The host (name or ip) of the API. Example: \'swagger.io\'';
		$properties->host->pattern = '^[^{}/ :\\\\]+(?::\\d+)?$';
		$properties->basePath = JsonBasicSchema::string();
		$properties->basePath->description = 'The base path to the API. Example: \'/api\'.';
		$properties->basePath->pattern = '^/';
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
		$properties->consumes = new JsonBasicSchema();
		$properties->consumes->allOf[0] = JsonBasicSchema::arr();
		$properties->consumes->allOf[0]->items = JsonBasicSchema::string();
		$properties->consumes->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->consumes->allOf[0]->uniqueItems = true;
		$properties->consumes->description = 'A list of MIME types accepted by the API.';
		$properties->produces = new JsonBasicSchema();
		$properties->produces->allOf[0] = JsonBasicSchema::arr();
		$properties->produces->allOf[0]->items = JsonBasicSchema::string();
		$properties->produces->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->produces->allOf[0]->uniqueItems = true;
		$properties->produces->description = 'A list of MIME types the API can produce.';
		$properties->paths = JsonBasicSchema::object();
		$properties->paths->additionalProperties = false;
		$properties->paths->patternProperties['^x-'] = new JsonBasicSchema();
		$properties->paths->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->paths->patternProperties['^/'] = PathItem::schema();
		$properties->paths->description = 'Relative paths to the individual endpoints. They must be relative to the \'basePath\'.';
		$properties->definitions = JsonBasicSchema::object();
		$properties->definitions->additionalProperties = Schema::schema();
		$properties->definitions->description = 'One or more JSON objects describing the schemas being consumed and produced by the API.';
		$properties->parameters = JsonBasicSchema::object();
		$properties->parameters->additionalProperties = new JsonBasicSchema();
		$properties->parameters->additionalProperties->oneOf[0] = BodyParameter::schema();
		$properties->parameters->additionalProperties->oneOf[1] = JsonBasicSchema::object();
		$properties->parameters->additionalProperties->oneOf[1]->oneOf[0] = HeaderParameterSubSchema::schema();
		$properties->parameters->additionalProperties->oneOf[1]->oneOf[1] = FormDataParameterSubSchema::schema();
		$properties->parameters->additionalProperties->oneOf[1]->oneOf[2] = QueryParameterSubSchema::schema();
		$properties->parameters->additionalProperties->oneOf[1]->oneOf[3] = PathParameterSubSchema::schema();
		$properties->parameters->additionalProperties->oneOf[1]->required = array (
		  0 => 'name',
		  1 => 'in',
		  2 => 'type',
		);
		$properties->parameters->description = 'One or more JSON representations for parameters';
		$properties->responses = JsonBasicSchema::object();
		$properties->responses->additionalProperties = Response::schema();
		$properties->responses->description = 'One or more JSON representations for parameters';
		$properties->security = JsonBasicSchema::arr();
		$properties->security->items = JsonBasicSchema::object();
		$properties->security->items->additionalProperties = JsonBasicSchema::arr();
		$properties->security->items->additionalProperties->items = JsonBasicSchema::string();
		$properties->security->items->additionalProperties->uniqueItems = true;
		$properties->security->uniqueItems = true;
		$properties->securityDefinitions = JsonBasicSchema::object();
		$properties->securityDefinitions->additionalProperties = new JsonBasicSchema();
		$properties->securityDefinitions->additionalProperties->oneOf[0] = BasicAuthenticationSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[1] = ApiKeySecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[2] = Oauth2ImplicitSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[3] = Oauth2PasswordSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[4] = Oauth2ApplicationSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[5] = Oauth2AccessCodeSecurity::schema();
		$properties->tags = JsonBasicSchema::arr();
		$properties->tags->items = Tag::schema();
		$properties->tags->uniqueItems = true;
		$properties->externalDocs = ExternalDocs::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new JsonBasicSchema();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->id = 'http://swagger.io/v2/schema.json#';
		$ownerSchema->schema = 'http://json-schema.org/draft-04/schema#';
		$ownerSchema->title = 'A JSON Schema for Swagger 2.0 API.';
		$ownerSchema->required = array (
		  0 => 'swagger',
		  1 => 'info',
		  2 => 'paths',
		);
	}

	/**
	 * @param string $swagger The Swagger version of this document.
	 * @return $this
	 */
	public function setSwagger($swagger)
	{
		$this->swagger = $swagger;
		return $this;
	}

	/**
	 * @param Info $info General information about the API.
	 * @return $this
	 */
	public function setInfo($info)
	{
		$this->info = $info;
		return $this;
	}

	/**
	 * @param string $host The host (name or ip) of the API. Example: 'swagger.io'
	 * @return $this
	 */
	public function setHost($host)
	{
		$this->host = $host;
		return $this;
	}

	/**
	 * @param string $basePath The base path to the API. Example: '/api'.
	 * @return $this
	 */
	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;
		return $this;
	}

	/**
	 * @param string[]|array $schemes The transfer protocol of the API.
	 * @return $this
	 */
	public function setSchemes($schemes)
	{
		$this->schemes = $schemes;
		return $this;
	}

	/**
	 * @param string[]|array $consumes A list of MIME types accepted by the API.
	 * @return $this
	 */
	public function setConsumes($consumes)
	{
		$this->consumes = $consumes;
		return $this;
	}

	/**
	 * @param string[]|array $produces A list of MIME types the API can produce.
	 * @return $this
	 */
	public function setProduces($produces)
	{
		$this->produces = $produces;
		return $this;
	}

	/**
	 * @param PathItem[] $paths Relative paths to the individual endpoints. They must be relative to the 'basePath'.
	 * @return $this
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
		return $this;
	}

	/**
	 * @param Schema[] $definitions One or more JSON objects describing the schemas being consumed and produced by the API.
	 * @return $this
	 */
	public function setDefinitions($definitions)
	{
		$this->definitions = $definitions;
		return $this;
	}

	/**
	 * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[] $parameters One or more JSON representations for parameters
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @param Response[] $responses One or more JSON representations for parameters
	 * @return $this
	 */
	public function setResponses($responses)
	{
		$this->responses = $responses;
		return $this;
	}

	/**
	 * @param string[][]|array[][]|array $security
	 * @return $this
	 */
	public function setSecurity($security)
	{
		$this->security = $security;
		return $this;
	}

	/**
	 * @param BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[] $securityDefinitions
	 * @return $this
	 */
	public function setSecurityDefinitions($securityDefinitions)
	{
		$this->securityDefinitions = $securityDefinitions;
		return $this;
	}

	/**
	 * @param Tag[]|array $tags
	 * @return $this
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @param ExternalDocs $externalDocs information about external documentation
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}
}

