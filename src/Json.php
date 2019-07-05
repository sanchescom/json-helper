<?php

namespace Sanchescom\Support;

use ReflectionClass;
use Sanchescom\Support\Exceptions\JsonException;
use Sanchescom\Support\Exceptions\UnableDecodeJsonException;
use Sanchescom\Support\Exceptions\UnableEncodeJsonException;

/**
 * Class Json.
 */
final class Json
{
    /**
     * Decodes a JSON string.
     *
     * @see https://php.net/manual/en/function.json-decode.php
     *
     * @param string $json    The json string being decoded.
     *                        This function only works with UTF-8 encoded strings.
     * @param bool   $assoc   When TRUE, returned objects will be converted into associative arrays.
     * @param int    $depth   User specified recursion depth.
     * @param int    $options Bitmask of JSON_BIGINT_AS_STRING, JSON_INVALID_UTF8_IGNORE,
     *                        JSON_INVALID_UTF8_SUBSTITUTE, JSON_OBJECT_AS_ARRAY, JSON_THROW_ON_ERROR.
     *                        The behaviour of these constants is described on the JSON constants page.
     *
     * @throws \Sanchescom\Support\Exceptions\UnableDecodeJsonException
     *
     * @return mixed Returns the value encoded in json in appropriate PHP type.
     */
    public static function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $data = json_decode($json, $assoc, $depth, self::cleanUpOptions($options));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnableDecodeJsonException(json_last_error_msg(), json_last_error());
        }

        return $data;
    }

    /**
     * Returns the JSON representation of a value.
     *
     * @see https://php.net/manual/en/function.json-encode.php
     *
     * @param mixed $value   The value being encoded. Can be any type except a resource.
     *                       All string data must be UTF-8 encoded.
     * @param int   $options Bitmask consisting of JSON_FORCE_OBJECT, JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
     *                       JSON_HEX_APOS, JSON_INVALID_UTF8_IGNORE, JSON_INVALID_UTF8_SUBSTITUTE, JSON_NUMERIC_CHECK,
     *                       JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_PRESERVE_ZERO_FRACTION, JSON_PRETTY_PRINT,
     *                       JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE,
     *                       JSON_THROW_ON_ERROR. The behaviour of these constants is described on the JSON
     *                       constants page.
     * @param int   $depth   Set the maximum depth. Must be greater than zero.
     *
     * @throws \Sanchescom\Support\Exceptions\UnableEncodeJsonException
     *
     * @return string
     */
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        $string = json_encode($value, self::cleanUpOptions($options), $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnableEncodeJsonException(json_last_error_msg(), json_last_error());
        }
        return (string)$string;
    }

    /**
     * @param string $json
     *
     * @throws \Sanchescom\Support\Exceptions\UnableDecodeJsonException
     *
     * @return array
     */
    public static function asArray(string $json)
    {
        return self::decode($json, true);
    }

    /**
     * Create new instance of class from JSON.
     *
     * @param string $className
     * @param string $json
     *
     * @throws \ReflectionException
     * @throws \Sanchescom\Support\Exceptions\UnableDecodeJsonException
     *
     * @return object
     */
    public static function asInstanceOf(string $className, string $json)
    {
        $properties = self::asArray($json);

        return self::newInstanceOf($className, $properties);
    }

    /**
     * Create collection of instances from JSON.
     *
     * @param string $className
     * @param string $json
     *
     * @throws \ReflectionException
     * @throws \Sanchescom\Support\Exceptions\UnableDecodeJsonException
     *
     * @return array
     */
    public static function asCollectionOfInstances(string $className, string $json)
    {
        $items = self::asArray($json);
        $collection = [];

        foreach ($items as $item) {
            $collection[] = self::newInstanceOf($className, $item);
        }

        return $collection;
    }

    /**
     * @param string $json
     *
     * @return bool
     */
    public static function isValid(string $json)
    {
        try {
            self::decode($json);
        } catch (JsonException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Create new instance of class with passed properties.
     *
     * @param string $className
     * @param array $properties
     *
     * @throws \ReflectionException
     *
     * @return mixed
     */
    private static function newInstanceOf(string $className, array $properties = [])
    {
        $reflectionClass= new ReflectionClass($className);

        $newInstance = new $className;

        foreach ($properties as $key => $value) {
            $reflectionClass->getProperty($key)->setValue($newInstance, $value);
        }

        return $newInstance;
    }

    /**
     * Disable the throw-on-error option.
     *
     * @param int $options
     *
     * @return int
     */
    private static function cleanUpOptions(int $options): int
    {
        if (PHP_VERSION_ID >= 70300
            && defined('JSON_THROW_ON_ERROR')
            && $options >= JSON_THROW_ON_ERROR) {
            return $options - JSON_THROW_ON_ERROR;
        }

        return $options;
    }
}
