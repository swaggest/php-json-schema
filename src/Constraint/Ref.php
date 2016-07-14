<?php

namespace Yaoi\Schema\Constraint;


use Yaoi\Schema\AbstractConstraint;
use Yaoi\Schema\Exception;
use Yaoi\Schema\Schema;

class Ref extends AbstractConstraint
{
    public static function getSchemaKey()
    {
        return '$ref';
    }

    public $ref;

    /** @var Schema */
    public $constraintSchema;

    public function __construct($schemaValue, Schema $ownerSchema = null)
    {
        $this->ref = $schemaValue;
        if ($this->ref === '#') {
            $this->constraintSchema = $ownerSchema->getRootSchema();
            return;
        }

        if ($this->ref[0] === '#') {
            $path = explode('/', trim($this->ref, '#/'));
            $schemaData = $ownerSchema->getRootSchema()->getSchemaData();
            $branch = &$schemaData;
            while ($path) {
                $folder = array_shift($path);
                if (isset($branch[$folder])) {
                    $branch = &$branch[$folder];
                } else {
                    throw new \Exception('Could not resolve ' . $this->ref . ', ' . $folder);
                }
            }
            $this->constraintSchema = new Schema($branch, $ownerSchema);
            return;
        }

        throw new \Exception('Could not resolve ' . $this->ref);
    }

    public function import($data)
    {
        try {
            return $this->constraintSchema->import($data);
        } catch (Exception $exception) {
            $exception->pushStructureTrace('Ref:' . $this->ref);
            throw $exception;
        }
    }

    public function export($data)
    {
        try {
            return $this->constraintSchema->export($data);
        } catch (Exception $exception) {
            $exception->pushStructureTrace('Ref:' . $this->ref);
            throw $exception;
        }
    }


}