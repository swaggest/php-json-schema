<?php

namespace Yaoi\Schema\NG;


use Yaoi\Schema\Constraint\Definitions;
use Yaoi\Schema\Constraint\Properties;
use Yaoi\Schema\Constraint\Ref;
use Yaoi\Schema\Constraint\Type;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Structure\ObjectItem;

class Schema
{
    /** @var Type */
    public $type;

    /** @var Properties */
    public $properties;

    /** @var Definitions */
    public $definitions;

    /** @var Ref */
    public $ref;


    public function import($data)
    {
        $result = $data;
        if ($this->ref !== null) {
            $result = $this->ref->getSchema()->import($data);
        }

        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                throw new Exception('Invalid type');
            }
        }

        if ($this->properties !== null) {
            if (!$result instanceof ObjectItem) {
                $result = new ObjectItem();
            }
            $this->properties->import($data, $result);
        }


        return $result;
    }


    public function export($data) {
        $result = $data;
        if ($this->ref !== null) {
            $result = $this->ref->getSchema()->export($data);
        }


        if ($this->type !== null) {
            if (!$this->type->isValid($data)) {
                throw new Exception('Invalid type');
            }
        }

        if ($this->properties !== null && ($data instanceof ObjectItem)) {
            $result = $this->properties->export($data);
        }

        return $result;
    }


    /**
     * @param $ref
     * @return $this|mixed
     * @throws \Exception
     */
    public function getDefinition($ref)
    {
        if ($ref === '#') {
            return $this;
        }

        if (substr($ref, 0, 14) === '#/definitions/') {
            $defName = substr($ref, 14);
            if (isset($this->definitions[$defName])) {
                return $this->definitions[$defName];
            }
        }

        throw new \Exception('Could not resolve ' . $ref);
    }


}