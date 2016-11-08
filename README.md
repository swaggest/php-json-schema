# yaoi schema

JSON-schema inspired PHP versatile structures.

```
Schema:
    Ref
    Type
    Properties
    Items
```

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
