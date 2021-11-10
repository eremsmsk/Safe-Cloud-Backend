<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\GraphQL\Type\Definition;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Represents an upload type.
 *
 * @author Mahmood Bazdar <mahmood@bazdar.me>
 */
class UploadType extends ScalarType
{
    /** @var string */
    public $name = Type::UPLOADED;

    /** @var string */
    public $description = 'The `Upload` type represents a file to be uploaded in the same HTTP request as specified by [graphql-multipart-request-spec](https://github.com/jaydenseric/graphql-multipart-request-spec).';

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($value)
    {
        throw new Error('`Upload` cannot be serialized.');
    }

    /**
     * {@inheritdoc}
     */
    public function parseValue($value): UploadedFile
    {
        if (!$value instanceof UploadedFile) {
            throw new Error(sprintf('Could not get uploaded file, be sure to conform to GraphQL multipart request specification. Instead got: %s', Utils::printSafe($value)));
        }

        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if (gettype($valueNode) === UploadedFile::class) {
            throw new Error('`Upload` cannot be hardcoded in query, be sure to conform to GraphQL multipart request specification.', $valueNode);
        }

        return $valueNode;
    }
}
