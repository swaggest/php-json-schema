<?php

namespace Swaggest\JsonSchema\Tests\Helper;

use Swaggest\JsonSchema\Constraint\Properties;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

/**
 * User63
 */
class User63 extends ClassStructure
{
    /** @var int The person's ID. */
    public $id;

    /** @var string The person's first name. */
    public $firstName;

    /** @var string The person's last name. */
    public $lastName;

    /** @var int Age in years which must be equal to or greater than zero. */
    public $age;

    /**
     * @param Properties|static $properties
     * @param Schema $ownerSchema
     */
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $properties->id = Schema::integer();
        $properties->id->description = "The person's ID.";
        $properties->firstName = Schema::string();
        $properties->firstName->description = "The person's first name.";
        $ownerSchema->addPropertyMapping('first_name', self::names()->firstName);
        $properties->lastName = Schema::string();
        $properties->lastName->description = "The person's last name.";
        $ownerSchema->addPropertyMapping('last_name', self::names()->lastName);
        $properties->age = Schema::integer();
        $properties->age->description = "Age in years which must be equal to or greater than zero.";
        $properties->age->minimum = 0;
        $ownerSchema->type = 'object';
        $ownerSchema->additionalProperties = false;
        $ownerSchema->schema = "http://json-schema.org/draft-07/schema#";
        $ownerSchema->title = "User";
        $ownerSchema->required = array(
            0 => 'id',
            1 => 'first_name',
            2 => 'last_name',
            3 => 'age',
        );
    }

    /**
     * @return int
     * @codeCoverageIgnoreStart
     */
    public function getId()
    {
        return $this->id;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $id The person's ID.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $firstName The person's first name.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param string $lastName The person's last name.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @return int
     * @codeCoverageIgnoreStart
     */
    public function getAge()
    {
        return $this->age;
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * @param int $age Age in years which must be equal to or greater than zero.
     * @return $this
     * @codeCoverageIgnoreStart
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }
    /** @codeCoverageIgnoreEnd */
}