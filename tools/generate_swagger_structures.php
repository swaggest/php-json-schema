<?php

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\RemoteRef\Preloaded;
use Swaggest\JsonSchema\Schema;
use Swaggest\PhpCodeBuilder\JsonSchema\PhpBuilder;
use Swaggest\PhpCodeBuilder\PhpCode;
use Swaggest\PhpCodeBuilder\PhpFile;

require_once __DIR__ . '/../vendor/autoload.php';

$schemaData = json_decode(file_get_contents(__DIR__ . '/../spec/swagger-schema.json'));

$refProvider = new Preloaded();
$refProvider->setSchemaData('http://swagger.io/v2/schema.json', $schemaData);

$options = new Context();
$options->setRemoteRefProvider($refProvider);


$swaggerSchema = Schema::import($schemaData, $options);

$builder = new PhpBuilder();
$builder->buildSetters = true;
$builder->makeEnumConstants = true;

$builder->getType($swaggerSchema); // #->paths->^/->get->parameters->items

$classes = array();
$files = array();
foreach ($builder->getGeneratedClasses() as $class) {
    $phpFile = new PhpFile();
    $phpCode = $phpFile->getCode();

    $namespace = 'Swaggest\\JsonSchema\\SwaggerSchema';
    $phpFile->setNamespace($namespace);

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

    if ($class->path === '#') {
        $className = 'SwaggerSchema';
    } else {
        $schema = $class->schema;
        $path = $class->path;
        if ($schema->getFromRef()) {
            $path = $schema->getFromRef();
        }
        $path = str_replace('#/definitions/', '', $path);
        $className = PhpCode::makePhpName($path, false);
    }
    if (!isset($classes[$className])) {
        $classes[$className] = 1;
        $class->class->setName($className);
        $class->class->setNamespace($namespace);
        $phpCode->addSnippet($class->class);
        $phpCode->addSnippet("\n\n");

        $files[$className] = $phpFile;
    }
}

$dir = __DIR__ . '/../src/SwaggerSchema';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}
foreach ($files as $className => $phpFile) {
    file_put_contents($dir . '/' . $className . '.php', (string)$phpFile);
}
