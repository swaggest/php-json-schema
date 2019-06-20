<?php

namespace Swaggest\JsonSchema;


class Helper
{
    /**
     * @param string $jsonPattern
     * @return string
     */
    public static function toPregPattern($jsonPattern)
    {
        return '{' . $jsonPattern . '}u';
    }

    /**
     * @param string $parent
     * @param string $current
     * @return string
     * @todo getaway from zeroes
     */
    public static function resolveURI($parent, $current)
    {
        if ($current === '') {
            return $parent;
        }

        $parentParts = explode('#', $parent, 2);
        $currentParts = explode('#', $current, 2);

        $resultParts = array($parentParts[0], '');
        if (isset($currentParts[1])) {
            $resultParts[1] = $currentParts[1];
        }

        if (isset($currentParts[0]) && $currentParts[0]) {
            if (strpos($currentParts[0], '://')) {
                $resultParts[0] = $currentParts[0];
            } elseif ('/' === substr($currentParts[0], 0, 1)) {
                $resultParts[0] = $currentParts[0];
                if (($pos = strpos($parentParts[0], '://'))
                    && ($len = strpos($parentParts[0], '/', $pos + 3))) {
                    $resultParts[0] = substr($parentParts[0], 0, $len) . $resultParts[0];
                }
            } elseif (false !== $pos = strrpos($parentParts[0], '/')) {
                $resultParts[0] = substr($parentParts[0], 0, $pos + 1) . $currentParts[0];
            } else {
                $resultParts[0] = $currentParts[0];
            }
        }

        $result = $resultParts[0] . '#' . $resultParts[1];
        return $result;
    }


    public static function padLines($with, $text, $skipFirst = true)
    {
        $lines = explode("\n", $text);
        foreach ($lines as $index => $line) {
            if ($skipFirst && !$index) {
                continue;
            }
            if ($line) {
                $lines[$index] = $with . $line;
            }
        }
        return implode("\n", $lines);
    }

}