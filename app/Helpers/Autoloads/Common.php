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
