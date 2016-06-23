# Schema elements

## `Schema`

`Schema` is a main unit of structured data. 
It holds a set of `Constraint` (can be empty).
Empty `Schema` can be created.
`Schema` MAY have root `Schema`.
`Schema` MAY have parent `Schema`.

`Schema` can be built from json decoded structure.
Parent and root need to be propagated to all children `(?)`.

(fuck root, only having parent no propagation)


## `Constraint`

`Constraint` is data descriptor. Some `Constraint` items are not fully 
independent, as they only make sense combined with another `Constraint`.
Therefore one `Constraint` can be an optional flag for another.

`Constraint` always has `RootSchema` and `ParentSchema` (owner).

## `Transformer`

`Transformer` is a special `Constraint` which imports/validates 
`RawData` to `Structure`.

## Primitive example

```php

// nice structure
$structure = StringStructure::create()
    ->setFormat('uri')
    ->setMinLength(10);

// fallback mode
$structure = StringStructure::create()->setSchemaData(array(
                                                          'format' => 'uri',
                                                          'minLength' => 10,
                                                      ));

// dumb mode
$structure = new Schema(array(
                            'type' => 'string',
                            'format' => 'uri',
                            'minLength' => 10,
                        ));

// inside StringStructure has Schema with data of
// {"type": "string", "format": "uri", "minLength": 10}
    
$structure->import(123); // Exception
$structure->import('http://sfsdf.sdfsd/'); // OK
```


## Solid detection

Use `\stdClass` instead of `array` for `JSON` objects, 
cast to `array` on iteration