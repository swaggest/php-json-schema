<?php

namespace Swaggest\JsonSchema\Path;

use Swaggest\JsonDiff\JsonPointer;
use Swaggest\JsonSchema\Schema;

class PointerUtil
{
    /**
     * Builds JSON pointer to schema from processing path
     * Path example: #->properties:responses->additionalProperties:envvar->properties:schema
     * @param string $path
     * @param bool $isURIFragmentId
     * @return string|null
     */
    public static function getSchemaPointer($path, $isURIFragmentId = false)
    {
        $result = self::getSchemaPointers($path, $isURIFragmentId);
        return array_pop($result);
    }

    /**
     * Builds JSON pointer to schema from processing path
     * Path example: #->properties:responses->additionalProperties:envvar->properties:schema
     * @param string $path
     * @param bool $isURIFragmentId
     * @return string[]
     */
    public static function getSchemaPointers($path, $isURIFragmentId = false)
    {
        $items = explode('->', $path);
        unset($items[0]);
        $result = array();
        $pointer = $isURIFragmentId ? '#' : '';
        foreach ($items as $item) {
            $parts = explode(':', $item);
            if (isset($parts[0])) {
                $schemaPaths = explode('[', $parts[0], 2);
                if (isset($schemaPaths[1])) {
                    $schemaPaths[1] = substr(strtr($schemaPaths[1], array('~1' => '~', '~2' => ':')), 0, -1);
                }

                if ($schemaPaths[0] === Schema::PROP_REF) {
                    $result[] = $pointer . '/' . JsonPointer::escapeSegment(Schema::PROP_REF, $isURIFragmentId);
                    if ($schemaPaths[1][0] !== '#') { // Absolute URI.
                        $pointer = $schemaPaths[1];
                    } else {
                        $pointer = self::rebuildPointer($schemaPaths[1], $isURIFragmentId);
                    }
                    continue;
                }
                $pointer .= '/' . JsonPointer::escapeSegment($schemaPaths[0], $isURIFragmentId);
                if (isset($schemaPaths[1])) {
                    $pointer .= '/' . JsonPointer::escapeSegment($schemaPaths[1], $isURIFragmentId);
                } elseif (isset($parts[1])) {
                    $pointer .= '/' . JsonPointer::escapeSegment($parts[1], $isURIFragmentId);
                }
            }
        }
        if ($pointer === '') {
            $pointer = '/';
        }
        $result[] = $pointer;
        return $result;
    }


    /**
     * Builds JSON pointer to data from processing path
     * Path example: #->properties:responses->additionalProperties:envvar->properties:schema
     * @param string $path
     * @param bool $isURIFragmentId
     * @return string
     */
    public static function getDataPointer($path, $isURIFragmentId = false)
    {
        $items = explode('->', $path);
        unset($items[0]);
        $result = $isURIFragmentId ? '#' : '';
        foreach ($items as $item) {
            $parts = explode(':', $item);
            if (isset($parts[1])) {
                $result .= '/' . JsonPointer::escapeSegment($parts[1], $isURIFragmentId);
            }
        }
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private static function rebuildPointer($pointer, $isURIFragmentId = false)
    {
        $parts = JsonPointer::splitPath($pointer);
        $result = $isURIFragmentId ? '#' : '';
        foreach ($parts as $item) {
            $result .= '/' . JsonPointer::escapeSegment($item, $isURIFragmentId);
        }
        return $result;
    }

}