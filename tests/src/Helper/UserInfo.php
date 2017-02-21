<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class UserInfo extends ClassStructure
{
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
        $properties->birthDay->format = Schema::FORMAT_DATE_TIME;
    }
}
