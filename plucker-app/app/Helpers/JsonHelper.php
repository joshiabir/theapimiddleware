<?php
namespace App\Helpers;

class JsonHelper {
    public static function replaceValuesWithTypes($data) {
        // Your function logic here
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::replaceValuesWithTypes($value);
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = self::replaceValuesWithTypes($value);
            }
        } else {
            if (is_string($data)) return 'String';
            if (is_int($data)) return 'Integer';
            if (is_float($data)) return 'Float';
            if (is_bool($data)) return 'Boolean';
            if (is_null($data)) return 'Null';
        }
        return $data;
    }

    public static function flattenJsonKeys($json, $prefix = '') {
        $result = [];
        foreach ($json as $key => $value) {
            if (is_numeric($key)) {
                $key = 'array';
            }
            $currentKey = $prefix ? $prefix . '.' . $key : $key;
            if (is_array($value) || is_object($value)) {
                // Recursively process nested arrays/objects
                $result = array_merge($result, self::flattenJsonKeys((array) $value, $currentKey));
            } else {
                // Add the final key-value pair
                $result[$currentKey] = $value;
            }
        }
        return $result;
    }
}
