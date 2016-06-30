<?php

namespace Yaoi\Schema\CodeBuilder;

use Yaoi\Schema\CodeBuilder\Templates\ClassStructurePhp;
use Yaoi\Schema\Exception;
use Yaoi\Schema\ObjectFlavour\Properties;
use Yaoi\Schema\Ref;
use Yaoi\Schema\Schema;
use Yaoi\Schema\Types\ArrayType;
use Yaoi\Schema\Types\BooleanType;
use Yaoi\Schema\Types\IntegerType;
use Yaoi\Schema\Types\NumberType;
use Yaoi\Schema\Types\ObjectType;
use Yaoi\Schema\Types\StringType;

class PHPCodeBuilder
{
    public $namespace;
    public $rootClassName;
    

    public function getSchemaInstantiationCode(Schema $schema)
    {
        foreach ($schema->getConstraints() as $constraintClass => $constraint) {
            
        }
        
        switch (true) {
            case StringType::getFromSchema($schema):
                return StringType::className() . '::makeSchema(' . ');';
        }

        if (ObjectType::getFromSchema($schema)) {
            if (Properties::getFromSchema($schema)) {
                return $this->getClassName($schema) . '::create()';
            }
        }
    }

    public function getTypeHint(Schema $schema)
    {
        if (StringType::getFromSchema($schema)) {

        }

    }

    public function getPhpDocType(Schema $schema)
    {
        if ($ref = Ref::getFromSchema($schema)) {
            return $this->getPhpDocType($ref->constraintSchema);
        }

        switch (true) {
            case StringType::getFromSchema($schema):
                return 'string';
            case IntegerType::getFromSchema($schema):
                return 'int';
            case NumberType::getFromSchema($schema):
                return 'float';
            case BooleanType::getFromSchema($schema):
                return 'bool';
            case ArrayType::getFromSchema($schema):
                return 'array'; // @todo resolve item
            case ObjectType::getFromSchema($schema):
                $this->resolveObject($schema);
                return 'object';
        }

        //throw new Exception("Please im");
        return '';
    }

    public $classes = array();

    public function resolveObject(Schema $schema)
    {
        if (Properties::getFromSchema($schema)) {


        }
        $path = $schema->getPath();
        //print_r($path);
        //print_r($schema->getSchemaData());
        return;
    }

    public function makeClass(Schema $schema, $className)
    {
        if ($objectType = ObjectType::getFromSchema($schema)) {
            if ($properties = Properties::getFromSchema($schema)) {
                $this->classes[$this->rootClassName] = ClassStructurePhp::create($schema, $this, $className, $this->namespace)->toString();
            }
        }
    }

    public function getClassName(Schema $schema)
    {
        $className = $this->rootClassName;
        if (!isset($this->classes[$className])) {
            $this->makeClass($schema, $className);
        }
        return $className;
    }


    public function toCamelCase($string, $lowerFirst = false)
    {
        $result = implode('', array_map('ucfirst', explode('_', $string)));
        if (!$result) {
            return '';
        }
        if ($lowerFirst) {
            $result[0] = strtolower($result[0]);
        }
        return $result;
    }


    public function makePhpName($rawName, $lowerFirst = true)
    {
        $phpName = preg_replace("/([^a-zA-Z0-9_]+)/", "_", $rawName);
        $phpName = $this->toCamelCase($phpName, $lowerFirst);
        if (!$phpName) {
            $phpName = 'property' . substr(md5($rawName), 0, 6);
        } elseif (is_numeric($phpName[0])) {
            $phpName = 'property' . $phpName;
        }
        return $phpName;
    }


    public function makeClassName($tracePath)
    {
        if (!$tracePath) {
            $path = 'Schema';
        } else {
            $path = $tracePath;
        }
        $path = $this->namespace . '\\' . $path;

        $path = str_replace(array('#', '/'), array('', '\\'), $path);

        $pathItems = explode('\\', $path);
        foreach ($pathItems as $key => &$pathItem) {
            if (!$pathItem) {
                unset($pathItems[$key]);
            } else {
                $pathItem = $this->makePhpName($pathItem, false);
            }
        }
        $className = '\\' . implode('\\', $pathItems);

        return $className;
    }


    public function storeToDisk($srcPath)
    {
        print_r($this->classes);
        
    }


}