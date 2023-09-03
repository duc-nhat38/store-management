<?php

if (!function_exists('isEmptyStringOrNull')) {
    /**
     * @param mixed $value
     * @return boolean
     */
    function isEmptyStringOrNull($value)
    {
        return $value === '' || is_null($value);
    }
}

if (!function_exists('isNotEmptyStringOrNull')) {
    /**
     * @param mixed $value
     * @return boolean
     */
    function isNotEmptyStringOrNull($value)
    {
        return !isEmptyStringOrNull($value);
    }
}

/**
 * Return the keyword when after format
 *
 * @return string
 */
if (!function_exists('escapeLike')) {
    /**
     * @param $keyword
     * @return array|string|string[]
     */
    function escapeLike($keyword)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $keyword);
    }
}
