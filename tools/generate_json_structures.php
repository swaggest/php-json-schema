<?php

use Swaggest\JsonSchema\Schema;
use Swaggest\PhpCodeBuilder\JsonSchema\PhpBuilder;
use Swaggest\PhpCodeBuilder\PhpCode;
use Swaggest\PhpCodeBuilder\PhpFile;

require_once __DIR__ . '/vendor/autoload.php';

$schemaData = json_decode(file_get_contents(__DIR__ . '/../spec/json-schema.json'));
$schema = Schema::schema()->in($schemaData);

$builder = new PhpBuilder();
$builder->buildSetters = true;
$builder->makeEnumConstants = true;

$builder->getType($schema);

$phpFile = new PhpFile();
$namespace = 'Swaggest\\JsonSchema';
$phpFile->setNamespace($namespace);

$phpCode = $phpFile->getCode();
$classesDone = array();
foreach ($builder->getGeneratedClasses() as $class) {
    if ($class->path === '#') {
        $class->class->setName('JsonSchema');
    } else {
        $path = str_replace('#/definitions/', '', $class->path);
        $class->class->setName(PhpCode::makePhpName($path, false));
    }

    $class->class->setNamespace($namespace);

    $desc = '';
    if ($class->schema->title) {
        $desc = $class->schema->title;
    }
    if ($class->schema->description) {
        $desc .= "\n" . $class->schema->description;
    }
    if ($class->schema->getFromRef()) {
        $desc .= "\nBuilt from " . $class->schema->getFromRef();
    }

    $class->class->setDescription(trim($desc));


    if (!isset($classesDone[$class->class->getName()])) {
        $className = $class->class->getName();
        $phpCode->addSnippet($class->class);
        $phpCode->addSnippet("\n\n");

        $classesDone[$className] = 1;
    }
}

$dir = __DIR__ . '/../src';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}
file_put_contents($dir . '/JsonSchema.php', (string)$phpFile);
