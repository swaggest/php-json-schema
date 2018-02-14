<?php

namespace Swaggest\JsonSchema\Constraint\Format;

class IdnHostname
{
    /**
     * @see http://www.unicode.org/faq/idn.html
     * @see https://gist.github.com/rxu/0660eef7a2f9e7992db6
     * @param string $data
     * @return null|string
     */
    public static function validationError($data)
    {
        $error = Iri::unicodeValidationError($data, $sanitized);
        if ($error !== null) {
            return $error;
        }
        return preg_match(Uri::HOSTNAME_REGEX, $sanitized) ? null : 'Invalid idn-hostname: ' . $data;
    }
}