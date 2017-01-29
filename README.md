# Swaggest JSON-schema implementation for PHP

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
$schema = SchemaLoader::create()->readSchema(json_decode($schemaJson));
```

Validate data
```php
$schema->import(json_decode(<<<'JSON'
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
class Example extends ClassStructure
{
    /* Native (public) properties will be validated only on import and export of structure data */

    /** @var int */
    public $id;
    public $name;
    /** @var Order[] */
    public $orders;

    /**
     * Define your properties 
     *
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->id = Schema::integer();
        $properties->name = Schema::string();

        $properties->quantity = Schema::integer();
        $properties->quantity->minimum = 0;

        $properties->orders = Schema::create();
        $properties->orders->items = Order::schema();

        $ownerSchema->required = array(self::names()->id);
    }
}

/**
 * 
 */
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
        $properties->dateTime = Schema::string();
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
$example = new Example();
$example->quantity = -1; // Exception: Value more than 0 expected, -1 received
```

Validation of native properties is performed only on import/export
```php
$example = new Example();
$example->quantity = 10;
Example::export($example); // Exception: Required property missing: id
```

Error messages provide a path to invalid data
```php
$example = new Example();
$example->id = 1;
$example->name = 'John Doe';

$order = new Order();
$order->dateTime = (new \DateTime())->format(DATE_RFC3339);
$example->orders[] = $order;

Example::export($example); // Exception: Required property missing: id at #->properties:orders->items[0]
```