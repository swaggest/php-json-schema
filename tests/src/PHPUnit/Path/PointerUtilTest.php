<?php

namespace Swaggest\JsonSchema\Tests\PHPUnit\Path;


use Swaggest\JsonSchema\Path\PointerUtil;

class PointerUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDataPointer()
    {
        $path = '#->properties:responses->additionalProperties:envvar->properties:schema';
        $dataPointer = PointerUtil::getDataPointer($path);
        $this->assertSame('/responses/envvar/schema', $dataPointer);
    }

    public function testGetSchemaPointer()
    {
        $path = '#->properties:responses->additionalProperties:envvar->properties:schema';
        $schemaPointer = PointerUtil::getSchemaPointer($path);
        $this->assertSame('/properties/responses/additionalProperties/envvar/properties/schema', $schemaPointer);

        $path = '#->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]->anyOf[0]';
        $this->assertSame('/cde/anyOf/0', PointerUtil::getSchemaPointer($path));

        $path = '#->properties:root->patternProperties[^[a-zA-Z0-9_]+$]:zoo->oneOf[2]->$ref[#/cde]->anyOf[0]';
        $this->assertSame([
            '/properties/root/patternProperties/^[a-zA-Z0-9_]+$/oneOf/2/$ref',
            '/cde/anyOf/0'
        ], PointerUtil::getSchemaPointers($path));
    }

}