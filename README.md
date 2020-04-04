# Swaggest JSON-schema implementation for PHP

[![Build Status](https://travis-ci.org/swaggest/php-json-schema.svg?branch=master)](https://travis-ci.org/swaggest/php-json-schema)
[![Code Climate](https://codeclimate.com/github/swaggest/php-json-schema/badges/gpa.svg)](https://codeclimate.com/github/swaggest/php-json-schema)
[![codecov](https://codecov.io/gh/swaggest/php-json-schema/branch/master/graph/badge.svg)](https://codecov.io/gh/swaggest/php-json-schema)
![Code lines](https://sloc.xyz/github/swaggest/php-json-schema/?category=code)
![Comments](https://sloc.xyz/github/swaggest/php-json-schema/?category=comments)

High definition PHP structures with JSON-schema based validation.

Supported schemas:
* [JSON Schema Draft 7](http://json-schema.org/specification-links.html#draft-7)
* [JSON Schema Draft 6](http://json-schema.org/specification-links.html#draft-6)
* [JSON Schema Draft 4](http://json-schema.org/specification-links.html#draft-4)

## Installation

```
composer require swaggest/json-schema
```

## Usage

Structure definition can be done either with `json-schema` or with
`PHP` class extending `Swaggest\JsonSchema\Structure\ClassStructure`

### Validating JSON data against given schema

Define your json-schema      
```php
$schemaJson = <<<'JSON'
{
    "type": "object",
    "properties": {
        "id": {
            "type": "integer"
        },
        "name": {
            "type": "string"
        },
        "orders": {
            "type": "array",
            "items": {
                "$ref": "#/definitions/order"
            }
        }
    },
    "required":["id"],
    "definitions": {
        "order": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "price": {
                    "type": "number"
                },
                "updated": {
                    "type": "string",
                    "format": "date-time"
                }
            },
            "required":["id"]
        }
    }
}
JSON;
```

Load it
```php
$schema = Schema::import(json_decode($schemaJson));
```

Validate data
```php
$schema->in(json_decode(<<<'JSON'
{
    "id": 1,
    "name":"John Doe",
    "orders":[
        {
            "id":1
        },
        {
            "price":1.0
        }
    ]
}
JSON
)); // Exception: Required property missing: id at #->properties:orders->items[1]->#/definitions/order
```

You can also call `Schema::import` on string `uri` to schema json data.
```php
$schema = Schema::import('http://localhost:1234/my_schema.json');
```

Or with boolean argument.
```php
$schema = Schema::import(true); // permissive schema, always validates
$schema = Schema::import(false); // restrictive schema, always invalidates
```

### Understanding error cause

With complex schemas it may be hard to find out what's wrong with your data. Exception message can look like:

```
No valid results for oneOf {
 0: Enum failed, enum: ["a"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[0]
 1: Enum failed, enum: ["b"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[1]
 2: No valid results for anyOf {
   0: Enum failed, enum: ["c"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]->anyOf[0]
   1: Enum failed, enum: ["d"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]->anyOf[1]
   2: Enum failed, enum: ["e"], data: "f" at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]->anyOf[2]
 } at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]
} at #->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo
```

For ambiguous schemas defined with `oneOf`/`anyOf` message is indented multi-line string.

Processing path is a combination of schema and data pointers. You can use `InvalidValue->getSchemaPointer()`
and `InvalidValue->getDataPointer()` to extract schema/data pointer.

You can receive `Schema` instance that failed validation with `InvalidValue->getFailedSubSchema`. 

You can build error tree using `InvalidValue->inspect()`.

### PHP structured classes with validation

```php
/**
 * @property int $quantity PHPDoc defined dynamic properties will be validated on every set
 */
class User extends ClassStructure
{
    /* Native (public) properties will be validated only on import and export of structure data */

    /** @var int */
    public $id;
    public $name;
    /** @var Order[] */
    public $orders;

    /** @var UserInfo */
    public $info;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        // You can add custom meta to your schema
        $dbTable = new DbTable;
        $dbTable->tableName = 'users';
        $ownerSchema->addMeta($dbTable);

        // Setup property schemas
        $properties->id = Schema::integer();
        $properties->id->addMeta(new DbId($dbTable)); // You can add meta to property.

        $properties->name = Schema::string();

        // You can embed structures to main level with nested schemas
        $properties->info = UserInfo::schema()->nested();

        // You can set default value for property
        $defaultOptions = new UserOptions();
        $defaultOptions->autoLogin = true;
        $defaultOptions->groupName = 'guest';
        // UserOptions::schema() is safe to change as it is protected with lazy cloning
        $properties->options = UserOptions::schema()->setDefault(UserOptions::export($defaultOptions));

        // Dynamic (phpdoc-defined) properties can be used as well
        $properties->quantity = Schema::integer();
        $properties->quantity->minimum = 0;

        // Property can be any complex structure
        $properties->orders = Schema::create();
        $properties->orders->items = Order::schema();

        $ownerSchema->required = array(self::names()->id);
    }
}

class UserInfo extends ClassStructure {
    public $firstName;
    public $lastName;
    public $birthDay;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->firstName = Schema::string();
        $properties->lastName = Schema::string();
        $properties->birthDay = Schema::string();
    }
}

class UserOptions extends ClassStructure
{
    public $autoLogin;
    public $groupName;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->autoLogin = Schema::boolean();
        $properties->groupName = Schema::string();
    }
}

class Order implements ClassStructureContract
{
    use ClassStructureTrait; // You can use trait if you can't/don't want to extend ClassStructure

    const FANCY_MAPPING = 'fAnCy'; // You can create additional mapping namespace

    public $id;
    public $userId;
    public $dateTime;
    public $price;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        // Add some meta data to your schema
        $dbMeta = new DbTable();
        $dbMeta->tableName = 'orders';
        $ownerSchema->addMeta($dbMeta);

        // Define properties
        $properties->id = Schema::integer();
        $properties->userId = User::properties()->id; // referencing property of another schema keeps meta
        $properties->dateTime = Schema::string();
        $properties->dateTime->format = Format::DATE_TIME;
        $properties->price = Schema::number();

        $ownerSchema->required[] = self::names()->id;

        // Define default mapping if any
        $ownerSchema->addPropertyMapping('date_time', Order::names()->dateTime);

        // Define additional mapping
        $ownerSchema->addPropertyMapping('DaTe_TiMe', Order::names()->dateTime, self::FANCY_MAPPING);
        $ownerSchema->addPropertyMapping('Id', Order::names()->id, self::FANCY_MAPPING);
        $ownerSchema->addPropertyMapping('PrIcE', Order::names()->price, self::FANCY_MAPPING);
    }
}
```

Validation of dynamic properties is performed on set, 
this can help to find source of invalid data at cost of 
some performance drop
```php
$user = new User();
$user->quantity = -1; // Exception: Value more than 0 expected, -1 received
```

Validation of native properties is performed only on import/export
```php
$user = new User();
$user->quantity = 10;
User::export($user); // Exception: Required property missing: id
```

Error messages provide a path to invalid data
```php
$user = new User();
$user->id = 1;
$user->name = 'John Doe';

$order = new Order();
$order->dateTime = (new \DateTime())->format(DATE_RFC3339);
$user->orders[] = $order;

User::export($user); // Exception: Required property missing: id at #->properties:orders->items[0]
```

#### Nested structures

Nested structures allow you to make composition: flatten several objects in one and separate back.

```php
$user = new User();
$user->id = 1;

$info = new UserInfo();
$info->firstName = 'John';
$info->lastName = 'Doe';
$info->birthDay = '1970-01-01';
$user->info = $info;

$json = <<<JSON
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "birthDay": "1970-01-01"
}
JSON;
$exported = User::export($user);
$this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

$imported = User::import(json_decode($json));
$this->assertSame('John', $imported->info->firstName);
$this->assertSame('Doe', $imported->info->lastName);
```

You can also use `\Swaggest\JsonSchema\Structure\Composition` to dynamically create schema compositions.
This can be helpful to deal with results of database query on joined data.

```php
$schema = new Composition(UserInfo::schema(), Order::schema());
$json = <<<JSON
{
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "price": 2.66
}
JSON;
$object = $schema->import(json_decode($json));

// Get particular object with `pick` accessor
$info = UserInfo::pick($object);
$order = Order::pick($object);

// Data is imported objects of according classes
$this->assertTrue($order instanceof Order);
$this->assertTrue($info instanceof UserInfo);

$this->assertSame(1, $order->id);
$this->assertSame('John', $info->firstName);
$this->assertSame('Doe', $info->lastName);
$this->assertSame(2.66, $order->price);
```

#### Keys mapping

If property names of PHP objects should be different from raw data you 
can call `->addPropertyMapping` on owner schema.

```php
// Define default mapping if any
$ownerSchema->addPropertyMapping('date_time', Order::names()->dateTime);

// Define additional mapping
$ownerSchema->addPropertyMapping('DaTe_TiMe', Order::names()->dateTime, self::FANCY_MAPPING);
$ownerSchema->addPropertyMapping('Id', Order::names()->id, self::FANCY_MAPPING);
$ownerSchema->addPropertyMapping('PrIcE', Order::names()->price, self::FANCY_MAPPING);
```

It will affect data mapping:
```php
$order = new Order();
$order->id = 1;
$order->dateTime = '2015-10-28T07:28:00Z';
$order->price = 2.2;
$exported = Order::export($order);
$json = <<<JSON
{
    "id": 1,
    "date_time": "2015-10-28T07:28:00Z",
    "price": 2.2
}
JSON;
$this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

$imported = Order::import(json_decode($json));
$this->assertSame('2015-10-28T07:28:00Z', $imported->dateTime);
```

You can have multiple mapping namespaces, controlling with `mapping` property of `Context`
```php
$options = new Context();
$options->mapping = Order::FANCY_MAPPING;

$exported = Order::export($order, $options);
$json = <<<JSON
{
    "Id": 1,
    "DaTe_TiMe": "2015-10-28T07:28:00Z",
    "PrIcE": 2.2
}
JSON;
$this->assertSame($json, json_encode($exported, JSON_PRETTY_PRINT));

$imported = Order::import(json_decode($json), $options);
$this->assertSame('2015-10-28T07:28:00Z', $imported->dateTime);
```

You can create your own pre-processor implementing `Swaggest\JsonSchema\DataPreProcessor`.

#### Meta

`Meta` is a way to complement `Schema` with your own data. You can keep and retrieve it.

You can store it.
```php
$dbMeta = new DbTable();
$dbMeta->tableName = 'orders';
$ownerSchema->addMeta($dbMeta);
```

And get back.
```php
// Retrieving meta
$dbTable = DbTable::get(Order::schema());
$this->assertSame('orders', $dbTable->tableName);
```


#### Mapping without validation

If you want to tolerate invalid data or improve mapping performance you can specify `skipValidation` flag in processing `Context`

```php
$schema = Schema::object();
$schema->setProperty('one', Schema::integer());
$schema->properties->one->minimum = 5;

$options = new Context();
$options->skipValidation = true;

$res = $schema->in(json_decode('{"one":4}'), $options);
$this->assertSame(4, $res->one);
```


#### Overriding mapping classes

If you want to map data to a different class you can register mapping at top level of your importer structure.

```php
class CustomSwaggerSchema extends SwaggerSchema
{
    public static function import($data, Context $options = null)
    {
        if ($options === null) {
            $options = new Context();
        }
        $options->objectItemClassMapping[Schema::className()] = CustomSchema::className();
        return parent::import($data, $options);
    }
}
```

Or specify it in processing context

```php
$context = new Context();
$context->objectItemClassMapping[Schema::className()] = CustomSchema::className();
$schema = SwaggerSchema::schema()->in(json_decode(
    file_get_contents(__DIR__ . '/../../../../spec/petstore-swagger.json')
), $context);
$this->assertInstanceOf(CustomSchema::className(), $schema->definitions['User']);
```

## Code quality and test coverage

Some code quality best practices are deliberately violated here
(see [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/swaggest/php-json-schema/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/swaggest/php-json-schema/?branch=master)
) to allow best performance at maintenance cost.

Those violations are secured by comprehensive test coverage:
 * draft-04, draft-06, draft-07 of [JSON-Schema-Test-Suite](https://github.com/json-schema-org/JSON-Schema-Test-Suite)
 * test cases (excluding `$data` and few tests) of [epoberezkin/ajv](https://github.com/epoberezkin/ajv/tree/master/spec) (a mature js implementation)

## Contributing

Issues and pull requests are welcome!

[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/0)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/0)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/1)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/1)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/2)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/2)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/3)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/3)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/4)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/4)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/5)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/5)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/6)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/6)[![](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/images/7)](https://sourcerer.io/fame/vearutop/swaggest/php-json-schema/links/7)

