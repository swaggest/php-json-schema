<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Meta\FieldName;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

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
