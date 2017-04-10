<?php

namespace Swaggest\JsonSchema\Constraint;

class Ref implements Constraint
{
    public $ref;
    public function __construct($ref, $data = null)
    {
        $this->ref = $ref;
        $this->data = $data;
    }

    /** @var mixed */
    private $data;

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
     * @throws \Exception
     */
    public function getData()
    {
        return $this->data;
    }
}