<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Format;
use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;


class UserInfo extends ClassStructure
{
    public $id;
    public $firstName;
    public $lastName;
    public $birthDay;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->id = User::properties()->id;
        $properties->firstName = Schema::string();
        $properties->lastName = Schema::string();
        $properties->birthDay = Schema::string();
        $properties->birthDay->format = Format::DATE_TIME;

        $ownerSchema->required[] = self::names()->id;
    }
}
