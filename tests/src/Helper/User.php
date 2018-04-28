<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

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

    /** @var UserOptions */
    public $options;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     * @throws \Exception
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
        $ownerSchema->setFromRef('#/definitions/user');
    }
}