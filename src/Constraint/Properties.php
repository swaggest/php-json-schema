<?php

namespace Yaoi\Schema\Constraint;

use Yaoi\Schema\MagicMap;
use Yaoi\Schema\Schema;

/**
 * @method Schema __get($key)
 */
class Properties extends MagicMap implements Constraint
{
    /** @var Schema[] */
    protected $_arrayOfData = array();

    public function __set($name, $column)
    {
        return parent::__set($name, $column);
    }

    public static function create()
    {
        return new static;
    }

    /** @var Schema */
    private $additionalProperties;

    /**
     * @param Schema $additionalProperties
     * @return Properties
     */
    public function setAdditionalProperties(Schema $additionalProperties = null)
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }
}