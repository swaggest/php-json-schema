<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructureContract;
use Swaggest\JsonSchema\Structure\ClassStructureTrait;

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

        $ownerSchema->setFromRef('#/definitions/order');

        // Define default mapping if any.
        $properties->addPropertyMapping('date_time', Order::names()->dateTime);

        // Use mapped name references after the default mapping was configured.
        $names = self::names($properties);
        $ownerSchema->required = array(
            $names->id,
            $names->dateTime,
            $names->price
        );

        // Define additional mapping
        $properties->addPropertyMapping('DaTe_TiMe', Order::names()->dateTime, self::FANCY_MAPPING);
        $properties->addPropertyMapping('Id', Order::names()->id, self::FANCY_MAPPING);
        $properties->addPropertyMapping('PrIcE', Order::names()->price, self::FANCY_MAPPING);
    }
}
