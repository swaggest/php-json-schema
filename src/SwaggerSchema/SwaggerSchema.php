<?php

namespace Swaggest\JsonSchema\SwaggerSchema;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema as Schema1;
use Swaggest\JsonSchema\Structure\ClassStructure;


class SwaggerSchema extends ClassStructure {
	/** @var string */
	public $swagger;

	/** @var Info */
	public $info;

	/** @var string */
	public $host;

	/** @var string */
	public $basePath;

	/** @var string[]|array */
	public $schemes;

	/** @var string[]|array */
	public $consumes;

	/** @var string[]|array */
	public $produces;

	/** @var PathItem[] */
	public $paths;

	/** @var Schema[] */
	public $definitions;

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[] */
	public $parameters;

	/** @var Response[] */
	public $responses;

	/** @var string[][]|array[][]|array */
	public $security;

	/** @var BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[] */
	public $securityDefinitions;

	/** @var Tag[]|array */
	public $tags;

	/** @var ExternalDocs */
	public $externalDocs;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->swagger = Schema1::string();
		$properties->swagger->description = 'The Swagger version of this document.';
		$properties->swagger->enum = array (
		  0 => '2.0',
		);
		$properties->info = Info::schema();
		$properties->host = Schema1::string();
		$properties->host->description = 'The host (name or ip) of the API. Example: \'swagger.io\'';
		$properties->host->pattern = '^[^{}/ :\\\\]+(?::\\d+)?$';
		$properties->basePath = Schema1::string();
		$properties->basePath->description = 'The base path to the API. Example: \'/api\'.';
		$properties->basePath->pattern = '^/';
		$properties->schemes = Schema1::arr();
		$properties->schemes->items = Schema1::string();
		$properties->schemes->items->enum = array (
		  0 => 'http',
		  1 => 'https',
		  2 => 'ws',
		  3 => 'wss',
		);
		$properties->schemes->description = 'The transfer protocol of the API.';
		$properties->schemes->uniqueItems = true;
		$properties->consumes = new Schema1();
		$properties->consumes->allOf[0] = Schema1::arr();
		$properties->consumes->allOf[0]->items = Schema1::string();
		$properties->consumes->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->consumes->allOf[0]->uniqueItems = true;
		$properties->consumes->description = 'A list of MIME types accepted by the API.';
		$properties->produces = new Schema1();
		$properties->produces->allOf[0] = Schema1::arr();
		$properties->produces->allOf[0]->items = Schema1::string();
		$properties->produces->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->produces->allOf[0]->uniqueItems = true;
		$properties->produces->description = 'A list of MIME types the API can produce.';
		$properties->paths = Schema1::object();
		$properties->paths->additionalProperties = false;
		$properties->paths->patternProperties['^x-'] = new Schema1();
		$properties->paths->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->paths->patternProperties['^/'] = PathItem::schema();
		$properties->paths->description = 'Relative paths to the individual endpoints. They must be relative to the \'basePath\'.';
		$properties->definitions = Schema1::object();
		$properties->definitions->additionalProperties = Schema::schema();
		$properties->definitions->description = 'One or more JSON objects describing the schemas being consumed and produced by the API.';
		$properties->parameters = Schema1::object();
		$properties->parameters->additionalProperties = new Schema1();
		$properties->parameters->additionalProperties->oneOf[0] = BodyParameter::schema();
		$properties->parameters->additionalProperties->oneOf[1] = Schema1::object();
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
		$properties->responses = Schema1::object();
		$properties->responses->additionalProperties = Response::schema();
		$properties->responses->description = 'One or more JSON representations for parameters';
		$properties->security = Schema1::arr();
		$properties->security->items = Schema1::object();
		$properties->security->items->additionalProperties = Schema1::arr();
		$properties->security->items->additionalProperties->items = Schema1::string();
		$properties->security->items->additionalProperties->uniqueItems = true;
		$properties->security->uniqueItems = true;
		$properties->securityDefinitions = Schema1::object();
		$properties->securityDefinitions->additionalProperties = new Schema1();
		$properties->securityDefinitions->additionalProperties->oneOf[0] = BasicAuthenticationSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[1] = ApiKeySecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[2] = Oauth2ImplicitSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[3] = Oauth2PasswordSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[4] = Oauth2ApplicationSecurity::schema();
		$properties->securityDefinitions->additionalProperties->oneOf[5] = Oauth2AccessCodeSecurity::schema();
		$properties->tags = Schema1::arr();
		$properties->tags->items = Tag::schema();
		$properties->tags->uniqueItems = true;
		$properties->externalDocs = ExternalDocs::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
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
	 * @return string
	 */
	public function getSwagger()
	{
		return $this->swagger;
	}

	/**
	 * @param string $swagger
	 * @return $this
	 */
	public function setSwagger($swagger)
	{
		$this->swagger = $swagger;
		return $this;
	}

	/**
	 * @return Info
	 */
	public function getInfo()
	{
		return $this->info;
	}

	/**
	 * @param Info $info
	 * @return $this
	 */
	public function setInfo($info)
	{
		$this->info = $info;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 * @return $this
	 */
	public function setHost($host)
	{
		$this->host = $host;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}

	/**
	 * @param string $basePath
	 * @return $this
	 */
	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getSchemes()
	{
		return $this->schemes;
	}

	/**
	 * @param string[]|array $schemes
	 * @return $this
	 */
	public function setSchemes($schemes)
	{
		$this->schemes = $schemes;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getConsumes()
	{
		return $this->consumes;
	}

	/**
	 * @param string[]|array $consumes
	 * @return $this
	 */
	public function setConsumes($consumes)
	{
		$this->consumes = $consumes;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getProduces()
	{
		return $this->produces;
	}

	/**
	 * @param string[]|array $produces
	 * @return $this
	 */
	public function setProduces($produces)
	{
		$this->produces = $produces;
		return $this;
	}

	/**
	 * @return PathItem[]
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * @param PathItem[] $paths
	 * @return $this
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
		return $this;
	}

	/**
	 * @return Schema[]
	 */
	public function getDefinitions()
	{
		return $this->definitions;
	}

	/**
	 * @param Schema[] $definitions
	 * @return $this
	 */
	public function setDefinitions($definitions)
	{
		$this->definitions = $definitions;
		return $this;
	}

	/**
	 * @return BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[] $parameters
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @return Response[]
	 */
	public function getResponses()
	{
		return $this->responses;
	}

	/**
	 * @param Response[] $responses
	 * @return $this
	 */
	public function setResponses($responses)
	{
		$this->responses = $responses;
		return $this;
	}

	/**
	 * @return string[][]|array[][]|array
	 */
	public function getSecurity()
	{
		return $this->security;
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
	 * @return BasicAuthenticationSecurity[]|ApiKeySecurity[]|Oauth2ImplicitSecurity[]|Oauth2PasswordSecurity[]|Oauth2ApplicationSecurity[]|Oauth2AccessCodeSecurity[]
	 */
	public function getSecurityDefinitions()
	{
		return $this->securityDefinitions;
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
	 * @return Tag[]|array
	 */
	public function getTags()
	{
		return $this->tags;
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
	 * @return ExternalDocs
	 */
	public function getExternalDocs()
	{
		return $this->externalDocs;
	}

	/**
	 * @param ExternalDocs $externalDocs
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}
}

class Info extends ClassStructure {
	/** @var string */
	public $title;

	/** @var string */
	public $version;

	/** @var string */
	public $description;

	/** @var string */
	public $termsOfService;

	/** @var Contact */
	public $contact;

	/** @var License */
	public $license;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->title = Schema1::string();
		$properties->title->description = 'A unique and precise title of the API.';
		$properties->version = Schema1::string();
		$properties->version->description = 'A semantic version number of the API.';
		$properties->description = Schema1::string();
		$properties->description->description = 'A longer description of the API. Should be different from the title.  GitHub Flavored Markdown is allowed.';
		$properties->termsOfService = Schema1::string();
		$properties->termsOfService->description = 'The terms of service for the API.';
		$properties->contact = Contact::schema();
		$properties->license = License::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'General information about the API.';
		$ownerSchema->required = array (
		  0 => 'version',
		  1 => 'title',
		);
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 * @return $this
	 */
	public function setVersion($version)
	{
		$this->version = $version;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTermsOfService()
	{
		return $this->termsOfService;
	}

	/**
	 * @param string $termsOfService
	 * @return $this
	 */
	public function setTermsOfService($termsOfService)
	{
		$this->termsOfService = $termsOfService;
		return $this;
	}

	/**
	 * @return Contact
	 */
	public function getContact()
	{
		return $this->contact;
	}

	/**
	 * @param Contact $contact
	 * @return $this
	 */
	public function setContact($contact)
	{
		$this->contact = $contact;
		return $this;
	}

	/**
	 * @return License
	 */
	public function getLicense()
	{
		return $this->license;
	}

	/**
	 * @param License $license
	 * @return $this
	 */
	public function setLicense($license)
	{
		$this->license = $license;
		return $this;
	}
}

class Contact extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $url;

	/** @var string */
	public $email;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->name = Schema1::string();
		$properties->name->description = 'The identifying name of the contact person/organization.';
		$properties->url = Schema1::string();
		$properties->url->description = 'The URL pointing to the contact information.';
		$properties->url->format = 'uri';
		$properties->email = Schema1::string();
		$properties->email->description = 'The email address of the contact person/organization.';
		$properties->email->format = 'email';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'Contact information for the owners of the API.';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return $this
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}
}

class License extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $url;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the license type. It\'s encouraged to use an OSI compatible license.';
		$properties->url = Schema1::string();
		$properties->url->description = 'The URL pointing to the license.';
		$properties->url->format = 'uri';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
}

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

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array */
	public $parameters;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->ref = Schema1::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$properties->get = Operation::schema();
		$properties->put = Operation::schema();
		$properties->post = Operation::schema();
		$properties->delete = Operation::schema();
		$properties->options = Operation::schema();
		$properties->head = Operation::schema();
		$properties->patch = Operation::schema();
		$properties->parameters = Schema1::arr();
		$properties->parameters->items = new Schema1();
		$properties->parameters->items->oneOf[0] = new Schema1();
		$properties->parameters->items->oneOf[0]->oneOf[0] = BodyParameter::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1] = Schema1::object();
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
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
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
	 * @return Operation
	 */
	public function getGet()
	{
		return $this->get;
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
	 * @return Operation
	 */
	public function getPut()
	{
		return $this->put;
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
	 * @return Operation
	 */
	public function getPost()
	{
		return $this->post;
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
	 * @return Operation
	 */
	public function getDelete()
	{
		return $this->delete;
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
	 * @return Operation
	 */
	public function getOptions()
	{
		return $this->options;
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
	 * @return Operation
	 */
	public function getHead()
	{
		return $this->head;
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
	 * @return Operation
	 */
	public function getPatch()
	{
		return $this->patch;
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
	 * @return BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array $parameters
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}
}

class Operation extends ClassStructure {
	/** @var string[]|array */
	public $tags;

	/** @var string */
	public $summary;

	/** @var string */
	public $description;

	/** @var ExternalDocs */
	public $externalDocs;

	/** @var string */
	public $operationId;

	/** @var string[]|array */
	public $produces;

	/** @var string[]|array */
	public $consumes;

	/** @var BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array */
	public $parameters;

	/** @var Response[]|JsonReference[] */
	public $responses;

	/** @var string[]|array */
	public $schemes;

	/** @var bool */
	public $deprecated;

	/** @var string[][]|array[][]|array */
	public $security;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->tags = Schema1::arr();
		$properties->tags->items = Schema1::string();
		$properties->tags->uniqueItems = true;
		$properties->summary = Schema1::string();
		$properties->summary->description = 'A brief summary of the operation.';
		$properties->description = Schema1::string();
		$properties->description->description = 'A longer description of the operation, GitHub Flavored Markdown is allowed.';
		$properties->externalDocs = ExternalDocs::schema();
		$properties->operationId = Schema1::string();
		$properties->operationId->description = 'A unique identifier of the operation.';
		$properties->produces = new Schema1();
		$properties->produces->allOf[0] = Schema1::arr();
		$properties->produces->allOf[0]->items = Schema1::string();
		$properties->produces->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->produces->allOf[0]->uniqueItems = true;
		$properties->produces->description = 'A list of MIME types the API can produce.';
		$properties->consumes = new Schema1();
		$properties->consumes->allOf[0] = Schema1::arr();
		$properties->consumes->allOf[0]->items = Schema1::string();
		$properties->consumes->allOf[0]->items->description = 'The MIME type of the HTTP message.';
		$properties->consumes->allOf[0]->uniqueItems = true;
		$properties->consumes->description = 'A list of MIME types the API can consume.';
		$properties->parameters = Schema1::arr();
		$properties->parameters->items = new Schema1();
		$properties->parameters->items->oneOf[0] = new Schema1();
		$properties->parameters->items->oneOf[0]->oneOf[0] = BodyParameter::schema();
		$properties->parameters->items->oneOf[0]->oneOf[1] = Schema1::object();
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
		$properties->responses = Schema1::object();
		$properties->responses->additionalProperties = false;
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$'] = new Schema1();
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$']->oneOf[0] = Response::schema();
		$properties->responses->patternProperties['^([0-9]{3})$|^(default)$']->oneOf[1] = JsonReference::schema();
		$properties->responses->patternProperties['^x-'] = new Schema1();
		$properties->responses->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->responses->not = Schema1::object();
		$properties->responses->not->additionalProperties = false;
		$properties->responses->not->patternProperties['^x-'] = new Schema1();
		$properties->responses->not->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$properties->responses->description = 'Response objects names can either be any valid HTTP status code or \'default\'.';
		$properties->responses->minProperties = 1;
		$properties->schemes = Schema1::arr();
		$properties->schemes->items = Schema1::string();
		$properties->schemes->items->enum = array (
		  0 => 'http',
		  1 => 'https',
		  2 => 'ws',
		  3 => 'wss',
		);
		$properties->schemes->description = 'The transfer protocol of the API.';
		$properties->schemes->uniqueItems = true;
		$properties->deprecated = Schema1::boolean();
		$properties->deprecated->default = false;
		$properties->security = Schema1::arr();
		$properties->security->items = Schema1::object();
		$properties->security->items->additionalProperties = Schema1::arr();
		$properties->security->items->additionalProperties->items = Schema1::string();
		$properties->security->items->additionalProperties->uniqueItems = true;
		$properties->security->uniqueItems = true;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'responses',
		);
	}

	/**
	 * @return string[]|array
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @param string[]|array $tags
	 * @return $this
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSummary()
	{
		return $this->summary;
	}

	/**
	 * @param string $summary
	 * @return $this
	 */
	public function setSummary($summary)
	{
		$this->summary = $summary;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return ExternalDocs
	 */
	public function getExternalDocs()
	{
		return $this->externalDocs;
	}

	/**
	 * @param ExternalDocs $externalDocs
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOperationId()
	{
		return $this->operationId;
	}

	/**
	 * @param string $operationId
	 * @return $this
	 */
	public function setOperationId($operationId)
	{
		$this->operationId = $operationId;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getProduces()
	{
		return $this->produces;
	}

	/**
	 * @param string[]|array $produces
	 * @return $this
	 */
	public function setProduces($produces)
	{
		$this->produces = $produces;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getConsumes()
	{
		return $this->consumes;
	}

	/**
	 * @param string[]|array $consumes
	 * @return $this
	 */
	public function setConsumes($consumes)
	{
		$this->consumes = $consumes;
		return $this;
	}

	/**
	 * @return BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param BodyParameter[]|HeaderParameterSubSchema[]|FormDataParameterSubSchema[]|QueryParameterSubSchema[]|PathParameterSubSchema[]|JsonReference[]|array $parameters
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @return Response[]|JsonReference[]
	 */
	public function getResponses()
	{
		return $this->responses;
	}

	/**
	 * @param Response[]|JsonReference[] $responses
	 * @return $this
	 */
	public function setResponses($responses)
	{
		$this->responses = $responses;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getSchemes()
	{
		return $this->schemes;
	}

	/**
	 * @param string[]|array $schemes
	 * @return $this
	 */
	public function setSchemes($schemes)
	{
		$this->schemes = $schemes;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getDeprecated()
	{
		return $this->deprecated;
	}

	/**
	 * @param bool $deprecated
	 * @return $this
	 */
	public function setDeprecated($deprecated)
	{
		$this->deprecated = $deprecated;
		return $this;
	}

	/**
	 * @return string[][]|array[][]|array
	 */
	public function getSecurity()
	{
		return $this->security;
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
}

class ExternalDocs extends ClassStructure {
	/** @var string */
	public $description;

	/** @var string */
	public $url;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->description = Schema1::string();
		$properties->url = Schema1::string();
		$properties->url->format = 'uri';
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'information about external documentation';
		$ownerSchema->required = array (
		  0 => 'url',
		);
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
}

class BodyParameter extends ClassStructure {
	/** @var string */
	public $description;

	/** @var string */
	public $name;

	/** @var string */
	public $in;

	/** @var bool */
	public $required;

	/** @var Schema */
	public $schema;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'body',
		);
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->schema = Schema::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		  1 => 'in',
		  2 => 'schema',
		);
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return Schema
	 */
	public function getSchema()
	{
		return $this->schema;
	}

	/**
	 * @param Schema $schema
	 * @return $this
	 */
	public function setSchema($schema)
	{
		$this->schema = $schema;
		return $this;
	}
}

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

	/** @var ExternalDocs */
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

	/**
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

	/**
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return int
	 */
	public function getMaxProperties()
	{
		return $this->maxProperties;
	}

	/**
	 * @param int $maxProperties
	 * @return $this
	 */
	public function setMaxProperties($maxProperties)
	{
		$this->maxProperties = $maxProperties;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMinProperties()
	{
		return $this->minProperties;
	}

	/**
	 * @param int $minProperties
	 * @return $this
	 */
	public function setMinProperties($minProperties)
	{
		$this->minProperties = $minProperties;
		return $this;
	}

	/**
	 * @return string[]|array
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param string[]|array $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return Schema|bool
	 */
	public function getAdditionalProperties()
	{
		return $this->additionalProperties;
	}

	/**
	 * @param Schema|bool $additionalProperties
	 * @return $this
	 */
	public function setAdditionalProperties($additionalProperties)
	{
		$this->additionalProperties = $additionalProperties;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param array $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return Schema|Schema[]|array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param Schema|Schema[]|array $items
	 * @return $this
	 */
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}

	/**
	 * @return Schema[]|array
	 */
	public function getAllOf()
	{
		return $this->allOf;
	}

	/**
	 * @param Schema[]|array $allOf
	 * @return $this
	 */
	public function setAllOf($allOf)
	{
		$this->allOf = $allOf;
		return $this;
	}

	/**
	 * @return Schema[]
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * @param Schema[] $properties
	 * @return $this
	 */
	public function setProperties($properties)
	{
		$this->properties = $properties;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDiscriminator()
	{
		return $this->discriminator;
	}

	/**
	 * @param string $discriminator
	 * @return $this
	 */
	public function setDiscriminator($discriminator)
	{
		$this->discriminator = $discriminator;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @param bool $readOnly
	 * @return $this
	 */
	public function setReadOnly($readOnly)
	{
		$this->readOnly = $readOnly;
		return $this;
	}

	/**
	 * @return Xml
	 */
	public function getXml()
	{
		return $this->xml;
	}

	/**
	 * @param Xml $xml
	 * @return $this
	 */
	public function setXml($xml)
	{
		$this->xml = $xml;
		return $this;
	}

	/**
	 * @return ExternalDocs
	 */
	public function getExternalDocs()
	{
		return $this->externalDocs;
	}

	/**
	 * @param ExternalDocs $externalDocs
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}

	public function getExample()
	{
		return $this->example;
	}

	/**
	 * @param $example
	 * @return $this
	 */
	public function setExample($example)
	{
		$this->example = $example;
		return $this;
	}
}

class Xml extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $namespace;

	/** @var string */
	public $prefix;

	/** @var bool */
	public $attribute;

	/** @var bool */
	public $wrapped;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->name = Schema1::string();
		$properties->namespace = Schema1::string();
		$properties->prefix = Schema1::string();
		$properties->attribute = Schema1::boolean();
		$properties->attribute->default = false;
		$properties->wrapped = Schema1::boolean();
		$properties->wrapped->default = false;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 * @return $this
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 * @return $this
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAttribute()
	{
		return $this->attribute;
	}

	/**
	 * @param bool $attribute
	 * @return $this
	 */
	public function setAttribute($attribute)
	{
		$this->attribute = $attribute;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getWrapped()
	{
		return $this->wrapped;
	}

	/**
	 * @param bool $wrapped
	 * @return $this
	 */
	public function setWrapped($wrapped)
	{
		$this->wrapped = $wrapped;
		return $this;
	}
}

class HeaderParameterSubSchema extends ClassStructure {
	/** @var bool */
	public $required;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/** @var string */
	public $name;

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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'header',
		);
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'boolean',
		  3 => 'integer',
		  4 => 'array',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema = new Schema1();
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

class PrimitivesItems extends ClassStructure {
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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'integer',
		  3 => 'boolean',
		  4 => 'array',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

class FormDataParameterSubSchema extends ClassStructure {
	/** @var bool */
	public $required;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/** @var string */
	public $name;

	/** @var bool */
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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'formData',
		);
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->allowEmptyValue = Schema1::boolean();
		$properties->allowEmptyValue->description = 'allows sending a parameter by name only or with an empty value.';
		$properties->allowEmptyValue->default = false;
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'boolean',
		  3 => 'integer',
		  4 => 'array',
		  5 => 'file',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		  4 => 'multi',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema = new Schema1();
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAllowEmptyValue()
	{
		return $this->allowEmptyValue;
	}

	/**
	 * @param bool $allowEmptyValue
	 * @return $this
	 */
	public function setAllowEmptyValue($allowEmptyValue)
	{
		$this->allowEmptyValue = $allowEmptyValue;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

class QueryParameterSubSchema extends ClassStructure {
	/** @var bool */
	public $required;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/** @var string */
	public $name;

	/** @var bool */
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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->default = false;
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'query',
		);
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->allowEmptyValue = Schema1::boolean();
		$properties->allowEmptyValue->description = 'allows sending a parameter by name only or with an empty value.';
		$properties->allowEmptyValue->default = false;
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'boolean',
		  3 => 'integer',
		  4 => 'array',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		  4 => 'multi',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema = new Schema1();
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
	}

	/**
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getAllowEmptyValue()
	{
		return $this->allowEmptyValue;
	}

	/**
	 * @param bool $allowEmptyValue
	 * @return $this
	 */
	public function setAllowEmptyValue($allowEmptyValue)
	{
		$this->allowEmptyValue = $allowEmptyValue;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

class PathParameterSubSchema extends ClassStructure {
	/** @var bool */
	public $required;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/** @var string */
	public $name;

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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->required = Schema1::boolean();
		$properties->required->description = 'Determines whether or not this parameter is required or optional.';
		$properties->required->enum = array (
		  0 => true,
		);
		$properties->in = Schema1::string();
		$properties->in->description = 'Determines the location of the parameter.';
		$properties->in->enum = array (
		  0 => 'path',
		);
		$properties->description = Schema1::string();
		$properties->description->description = 'A brief description of the parameter. This could contain examples of use.  GitHub Flavored Markdown is allowed.';
		$properties->name = Schema1::string();
		$properties->name->description = 'The name of the parameter.';
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'boolean',
		  3 => 'integer',
		  4 => 'array',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$ownerSchema = new Schema1();
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'required',
		);
	}

	/**
	 * @return bool
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

class JsonReference extends ClassStructure {
	/** @var string */
	public $ref;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->ref = Schema1::string();
		$ownerSchema->addPropertyMapping('$ref', self::names()->ref);
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->required = array (
		  0 => '$ref',
		);
	}

	/**
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
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
}

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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->description = Schema1::string();
		$properties->schema = new Schema1();
		$properties->schema->oneOf[0] = Schema::schema();
		$properties->schema->oneOf[1] = FileSchema::schema();
		$properties->headers = Schema1::object();
		$properties->headers->additionalProperties = Header::schema();
		$properties->examples = Schema1::object();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'description',
		);
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return Schema|FileSchema
	 */
	public function getSchema()
	{
		return $this->schema;
	}

	/**
	 * @param Schema|FileSchema $schema
	 * @return $this
	 */
	public function setSchema($schema)
	{
		$this->schema = $schema;
		return $this;
	}

	/**
	 * @return Header[]
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param Header[] $headers
	 * @return $this
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;
		return $this;
	}

	public function getExamples()
	{
		return $this->examples;
	}

	/**
	 * @param $examples
	 * @return $this
	 */
	public function setExamples($examples)
	{
		$this->examples = $examples;
		return $this;
	}
}

class FileSchema extends ClassStructure {
	/** @var string */
	public $format;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	public $default;

	/** @var string[]|array */
	public $required;

	/** @var string */
	public $type;

	/** @var bool */
	public $readOnly;

	/** @var ExternalDocs */
	public $externalDocs;

	public $example;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->format = Schema1::string();
		$properties->title = Schema1::string();
		$properties->description = Schema1::string();
		$properties->default = new Schema1();
		$properties->required = Schema1::arr();
		$properties->required->items = Schema1::string();
		$properties->required->minItems = 1;
		$properties->required->uniqueItems = true;
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'file',
		);
		$properties->readOnly = Schema1::boolean();
		$properties->readOnly->default = false;
		$properties->externalDocs = ExternalDocs::schema();
		$properties->example = new Schema1();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->description = 'A deterministic version of a JSON Schema object.';
		$ownerSchema->required = array (
		  0 => 'type',
		);
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	public function getDefault()
	{
		return $this->default;
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
	 * @return string[]|array
	 */
	public function getRequired()
	{
		return $this->required;
	}

	/**
	 * @param string[]|array $required
	 * @return $this
	 */
	public function setRequired($required)
	{
		$this->required = $required;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return bool
	 */
	public function getReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @param bool $readOnly
	 * @return $this
	 */
	public function setReadOnly($readOnly)
	{
		$this->readOnly = $readOnly;
		return $this;
	}

	/**
	 * @return ExternalDocs
	 */
	public function getExternalDocs()
	{
		return $this->externalDocs;
	}

	/**
	 * @param ExternalDocs $externalDocs
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}

	public function getExample()
	{
		return $this->example;
	}

	/**
	 * @param $example
	 * @return $this
	 */
	public function setExample($example)
	{
		$this->example = $example;
		return $this;
	}
}

class Header extends ClassStructure {
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

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'string',
		  1 => 'number',
		  2 => 'integer',
		  3 => 'boolean',
		  4 => 'array',
		);
		$properties->format = Schema1::string();
		$properties->items = PrimitivesItems::schema();
		$properties->collectionFormat = Schema1::string();
		$properties->collectionFormat->default = 'csv';
		$properties->collectionFormat->enum = array (
		  0 => 'csv',
		  1 => 'ssv',
		  2 => 'tsv',
		  3 => 'pipes',
		);
		$properties->default = new Schema1();
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
		$properties->enum = Schema1::arr();
		$properties->enum->minItems = 1;
		$properties->enum->uniqueItems = true;
		$properties->multipleOf = Schema1::number();
		$properties->multipleOf->minimum = 0;
		$properties->multipleOf->exclusiveMinimum = true;
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return PrimitivesItems
	 */
	public function getItems()
	{
		return $this->items;
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
	 * @return string
	 */
	public function getCollectionFormat()
	{
		return $this->collectionFormat;
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

	public function getDefault()
	{
		return $this->default;
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
	 * @return float
	 */
	public function getMaximum()
	{
		return $this->maximum;
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
	 * @return bool
	 */
	public function getExclusiveMaximum()
	{
		return $this->exclusiveMaximum;
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
	 * @return float
	 */
	public function getMinimum()
	{
		return $this->minimum;
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
	 * @return bool
	 */
	public function getExclusiveMinimum()
	{
		return $this->exclusiveMinimum;
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
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->maxLength;
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
	 * @return int
	 */
	public function getMinLength()
	{
		return $this->minLength;
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
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
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
	 * @return int
	 */
	public function getMaxItems()
	{
		return $this->maxItems;
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
	 * @return int
	 */
	public function getMinItems()
	{
		return $this->minItems;
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
	 * @return bool
	 */
	public function getUniqueItems()
	{
		return $this->uniqueItems;
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
	 * @return array
	 */
	public function getEnum()
	{
		return $this->enum;
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
	 * @return float
	 */
	public function getMultipleOf()
	{
		return $this->multipleOf;
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

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class BasicAuthenticationSecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'basic',
		);
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class ApiKeySecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $name;

	/** @var string */
	public $in;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'apiKey',
		);
		$properties->name = Schema1::string();
		$properties->in = Schema1::string();
		$properties->in->enum = array (
		  0 => 'header',
		  1 => 'query',
		);
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'name',
		  2 => 'in',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIn()
	{
		return $this->in;
	}

	/**
	 * @param string $in
	 * @return $this
	 */
	public function setIn($in)
	{
		$this->in = $in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class Oauth2ImplicitSecurity extends ClassStructure {
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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'oauth2',
		);
		$properties->flow = Schema1::string();
		$properties->flow->enum = array (
		  0 => 'implicit',
		);
		$properties->scopes = Schema1::object();
		$properties->scopes->additionalProperties = Schema1::string();
		$properties->authorizationUrl = Schema1::string();
		$properties->authorizationUrl->format = 'uri';
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'authorizationUrl',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFlow()
	{
		return $this->flow;
	}

	/**
	 * @param string $flow
	 * @return $this
	 */
	public function setFlow($flow)
	{
		$this->flow = $flow;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @param string[] $scopes
	 * @return $this
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorizationUrl()
	{
		return $this->authorizationUrl;
	}

	/**
	 * @param string $authorizationUrl
	 * @return $this
	 */
	public function setAuthorizationUrl($authorizationUrl)
	{
		$this->authorizationUrl = $authorizationUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class Oauth2PasswordSecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $flow;

	/** @var string[] */
	public $scopes;

	/** @var string */
	public $tokenUrl;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'oauth2',
		);
		$properties->flow = Schema1::string();
		$properties->flow->enum = array (
		  0 => 'password',
		);
		$properties->scopes = Schema1::object();
		$properties->scopes->additionalProperties = Schema1::string();
		$properties->tokenUrl = Schema1::string();
		$properties->tokenUrl->format = 'uri';
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'tokenUrl',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFlow()
	{
		return $this->flow;
	}

	/**
	 * @param string $flow
	 * @return $this
	 */
	public function setFlow($flow)
	{
		$this->flow = $flow;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @param string[] $scopes
	 * @return $this
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTokenUrl()
	{
		return $this->tokenUrl;
	}

	/**
	 * @param string $tokenUrl
	 * @return $this
	 */
	public function setTokenUrl($tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class Oauth2ApplicationSecurity extends ClassStructure {
	/** @var string */
	public $type;

	/** @var string */
	public $flow;

	/** @var string[] */
	public $scopes;

	/** @var string */
	public $tokenUrl;

	/** @var string */
	public $description;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'oauth2',
		);
		$properties->flow = Schema1::string();
		$properties->flow->enum = array (
		  0 => 'application',
		);
		$properties->scopes = Schema1::object();
		$properties->scopes->additionalProperties = Schema1::string();
		$properties->tokenUrl = Schema1::string();
		$properties->tokenUrl->format = 'uri';
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'tokenUrl',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFlow()
	{
		return $this->flow;
	}

	/**
	 * @param string $flow
	 * @return $this
	 */
	public function setFlow($flow)
	{
		$this->flow = $flow;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @param string[] $scopes
	 * @return $this
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTokenUrl()
	{
		return $this->tokenUrl;
	}

	/**
	 * @param string $tokenUrl
	 * @return $this
	 */
	public function setTokenUrl($tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

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
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->type = Schema1::string();
		$properties->type->enum = array (
		  0 => 'oauth2',
		);
		$properties->flow = Schema1::string();
		$properties->flow->enum = array (
		  0 => 'accessCode',
		);
		$properties->scopes = Schema1::object();
		$properties->scopes->additionalProperties = Schema1::string();
		$properties->authorizationUrl = Schema1::string();
		$properties->authorizationUrl->format = 'uri';
		$properties->tokenUrl = Schema1::string();
		$properties->tokenUrl->format = 'uri';
		$properties->description = Schema1::string();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'type',
		  1 => 'flow',
		  2 => 'authorizationUrl',
		  3 => 'tokenUrl',
		);
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return string
	 */
	public function getFlow()
	{
		return $this->flow;
	}

	/**
	 * @param string $flow
	 * @return $this
	 */
	public function setFlow($flow)
	{
		$this->flow = $flow;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @param string[] $scopes
	 * @return $this
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorizationUrl()
	{
		return $this->authorizationUrl;
	}

	/**
	 * @param string $authorizationUrl
	 * @return $this
	 */
	public function setAuthorizationUrl($authorizationUrl)
	{
		$this->authorizationUrl = $authorizationUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTokenUrl()
	{
		return $this->tokenUrl;
	}

	/**
	 * @param string $tokenUrl
	 * @return $this
	 */
	public function setTokenUrl($tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
}

class Tag extends ClassStructure {
	/** @var string */
	public $name;

	/** @var string */
	public $description;

	/** @var ExternalDocs */
	public $externalDocs;

	/**
	 * @param Properties|static $properties
	 * @param Schema1 $ownerSchema
	 */
	public static function setUpProperties($properties, Schema1 $ownerSchema)
	{
		$properties->name = Schema1::string();
		$properties->description = Schema1::string();
		$properties->externalDocs = ExternalDocs::schema();
		$ownerSchema->type = 'object';
		$ownerSchema->additionalProperties = false;
		$ownerSchema->patternProperties['^x-'] = new Schema1();
		$ownerSchema->patternProperties['^x-']->description = 'Any property starting with x- is valid.';
		$ownerSchema->required = array (
		  0 => 'name',
		);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return ExternalDocs
	 */
	public function getExternalDocs()
	{
		return $this->externalDocs;
	}

	/**
	 * @param ExternalDocs $externalDocs
	 * @return $this
	 */
	public function setExternalDocs($externalDocs)
	{
		$this->externalDocs = $externalDocs;
		return $this;
	}
}

