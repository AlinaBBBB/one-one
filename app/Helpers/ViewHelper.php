// app/Helpers/ViewHelper.php
<?php

namespace App\Helpers;

class ViewHelper
{
    public static function safeGet($object, $property, $default = null)
    {
        if (is_object($object) && isset($object->$property)) {
            return $object->$property;
        }
        return $default;
    }
}