<?php

declare(strict_types=1);

namespace App\GraphQL\Type\Definition;

use GraphQL\Type\Definition\ScalarType;

/**
 * Registry of standard GraphQL types
 * and a base class for all other types.
 */
abstract class Type extends \GraphQL\Type\Definition\Type
{
    public const UPLOADED  = 'Uploaded';

    /**
     * @api
     */
    public static function uploaded() : ScalarType
    {
        if (! isset(static::$standardTypes[self::UPLOADED])) {
            static::$standardTypes[self::UPLOADED] = new UploadType();
        }

        return static::$standardTypes[self::UPLOADED];
    }

    /**
     * Returns all builtin scalar types
     *
     * @return ScalarType[]
     */
    public static function getStandardTypes()
    {
        return [
            self::ID => static::id(),
            self::STRING => static::string(),
            self::FLOAT => static::float(),
            self::INT => static::int(),
            self::BOOLEAN => static::boolean(),
            self::UPLOADED => static::uploaded(),
        ];
    }
}
