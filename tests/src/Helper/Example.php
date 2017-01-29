<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

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