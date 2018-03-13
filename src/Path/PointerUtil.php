<?php

namespace Swaggest\JsonSchema\Path;

use Swaggest\JsonDiff\JsonPointer;

class PointerUtil
{
    /**
     * Builds JSON pointer to data from processing path
     * Path example: #->properties:responses->additionalProperties:envvar->properties:schema
     * @param string $path
     * @return string
     * @todo proper path items escaping/moving to native JSON pointers
     */
    public static function getDataPointer($path) {
        $items = explode('->', $path);
        unset($items[0]);
        $result = '#';
        foreach ($items as $item) {
            $parts = explode(':', $item);
            if (isset($parts[1])) {
                $result .= '/' . JsonPointer::escapeSegment($parts[1], true);
            }
        }
        return $result;
    }

}