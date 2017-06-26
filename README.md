# Swaggest JSON-schema implementation for PHP

[![Build Status](https://travis-ci.org/swaggest/php-json-schema.svg?branch=master)](https://travis-ci.org/swaggest/php-json-schema)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/swaggest/php-json-schema/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/swaggest/php-json-schema/?branch=master)
[![Code Climate](https://codeclimate.com/github/swaggest/php-json-schema/badges/gpa.svg)](https://codeclimate.com/github/swaggest/php-json-schema)
[![Test Coverage](https://codeclimate.com/github/swaggest/php-json-schema/badges/coverage.svg)](https://codeclimate.com/github/swaggest/php-json-schema/coverage)

High definition PHP structures with JSON-schema based validation.

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
        // Setup property schemas
        $properties->id = Schema::integer();
        $properties->name = Schema::string();

        // You can embed structures to main level with nested schemas
        $properties->info = UserInfo::schema()->nested();

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


class Order extends ClassStructure
{
    public $id;
    public $dateTime;
    public $price;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->id = Schema::integer();
        $properties->dateTime = Schema::string()->meta(new FieldName('date_time'));
        $properties->dateTime->format = Schema::FORMAT_DATE_TIME;
        $properties->price = Schema::number();

        $ownerSchema->required[] = self::names()->id;
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
can apply `\Swaggest\JsonSchema\PreProcessor\NameMapper` during processing.
It takes `Swaggest\JsonSchema\Meta\FieldName` as source of raw name.

```php
$properties->dateTime = Schema::string()->meta(new FieldName('date_time'));
```

```php
$mapper = new NameMapper();
$options = new Context();
$options->dataPreProcessor = $mapper;

$order = new Order();
$order->id = 1;
$order->dateTime = '2015-10-28T07:28:00Z';
$exported = Order::export($order, $options);
$json = <<<JSON
{
    "id": 1,
    "date_time": "2015-10-28T07:28:00Z"
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
$schema = new Schema();
// Setting meta
$schema->meta(new FieldName('my-value'));
```

And get back.
```php
// Retrieving meta
$myMeta = FieldName::get($schema);
$this->assertSame('my-value', $myMeta->name);
```


#### Mapping without validation

If you want to tolerate invalid data or improve mapping performance you can specify `skipValidation` flag in processing `Context`

```
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

```
class CustomSwaggerSchema extends SwaggerSchema
{
    public static function setUpProperties($properties, JsonBasicSchema $ownerSchema)
    {
        parent::setUpProperties($properties, $ownerSchema);
        self::$objectItemClassMapping[Schema::className()] = CustomSchema::className();
    }
}
```

Or specify it in processing context

```
$context = new Context();
$context->objectItemClassMapping[Schema::className()] = CustomSchema::className();
$schema = SwaggerSchema::schema()->in(json_decode(
    file_get_contents(__DIR__ . '/../../../../spec/petstore-swagger.json')
), $context);
$this->assertInstanceOf(CustomSchema::className(), $schema->definitions['User']);
```