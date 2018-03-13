<?php

namespace Swaggest\JsonSchema\Constraint;

class Ref implements Constraint
{
    /** @var string */
    public $ref;
    public function __construct($ref, $data = null)
    {
        $this->ref = $ref;
        $this->data = $data;
    }

    /** @var mixed */
    private $data;

    /** @var mixed */
    private $imported;
    /** @var boolean */
    private $isImported = false;

    /**
     * @return mixed
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * @param mixed $imported
     */
    public function setImported($imported)
    {
        $this->isImported = true;
        $this->imported = $imported;
    }

    /**
     * @return boolean
     */
    public function isImported()
    {
        return $this->isImported;
    }

    public function unsetImported()
    {
        $this->isImported = false;
        $this->imported = null;
    }

    /**
     * @param mixed $data
     * @return Ref
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}