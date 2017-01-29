# Swaggest JSON-schema implementation for PHP

High definition PHP structures with JSON-schema based validation.

## Usage

### Validating JSON data against given schema

```php

```

### Validated PHP classes

```php
$schema = new Schema();

$schema->properties = Properties::create()->....;
$schema->type = Type::create('object');

$schema->reset();
$schema->import(json_decode('...'));

$schema->setPreserveNulls(true);
try {
    $data = $schema->import(json_decode('...'));
    $data->property->deeper->olala;
    $data->additionalProperties[0];
    $data->property->additionalProperties[0];
} catch (Exception $e) {
    $e->getMessage(); // Invalid value for properties::property->properties::deeper, object expected
}
```
