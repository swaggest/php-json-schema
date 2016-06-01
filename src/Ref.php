<?php

namespace Yaoi\Schema;


class Ref extends AbstractConstraint implements Transformer, Constraint
{
    const REF = '$ref';

    public $ref;

    /** @var Schema */
    public $rootSchema;

    /** @var Schema */
    private $parentSchema;

    /** @var Schema */
    public $constraintSchema;
    
    public function __construct($schemaValue, Schema $rootSchema = null, Schema $parentSchema = null)
    {
        $this->ref = $schemaValue;
        $this->rootSchema = $rootSchema;
        $this->parentSchema = $parentSchema;

        if ($this->ref === '#') {
            $this->constraintSchema = $rootSchema;
            return;
        }

        if ($this->ref[0] === '#') {
            $path = explode('/', trim($this->ref, '#/'));
            $schemaData = $rootSchema->getSchemaData();
            $branch = &$schemaData;
            while ($path) {
                $folder = array_shift($path);
                if (isset($branch[$folder])) {
                    $branch = &$branch[$folder];
                }
                else {
                    throw new \Exception('Could not resolve ' . $this->ref . ', ' . $folder);
                }
            }
            $this->constraintSchema = new Schema($branch, $this->rootSchema);
            return;
        }

        throw new \Exception('Could not resolve ' . $this->ref);
    }

    public function import($data)
    {
        return $this->constraintSchema->import($data);
    }

    public function export($data)
    {
        return $this->constraintSchema->export($data);
    }


}