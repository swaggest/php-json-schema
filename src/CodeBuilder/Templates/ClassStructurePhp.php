<?php

namespace Yaoi\Schema\CodeBuilder\Templates;


use Yaoi\Schema\Base;
use Yaoi\Schema\CodeBuilder\PHPCodeBuilder;
use Yaoi\Schema\Properties;
use Yaoi\Schema\Schema;

/**
 * @method static ClassStructurePhp create(Schema $schema, PHPCodeBuilder $codeBuilder, $className, $namespace)
 */
class ClassStructurePhp extends Base
{
    /** @var Schema  */
    private $schema;
    /** @var PHPCodeBuilder */
    private $codeBuilder;

    private $className;
    private $namespace;

    public function __construct(Schema $schema, PHPCodeBuilder $codeBuilder, $className, $namespace)
    {
        $this->schema = $schema;
        $this->codeBuilder = $codeBuilder;
        $this->className = $className;
        $this->namespace = $namespace;
    }


    private function getPhpDocHead()
    {
        $result = '';

        if ($properties = Properties::getFromSchema($this->schema)) {
            foreach ($properties->properties as $name => $property) {
                $phpDocType = $this->codeBuilder->getPhpDoc($property);
                $result .= <<<PHPDOC
 * @property $phpDocType $$name

PHPDOC;
            }
        }

        return $result;

    }

    public function toString()
    {
        $result = '';
        $phpDocHead = $this->getPhpDocHead();
        

        $result .= /** @lang PHP */<<<PHP
namespace $this->namespace;

/**
$phpDocHead
 */
class $this->className
{
    

}

PHP;
        return $result;


    }
}