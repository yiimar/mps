<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Http\Enum;

/**
 * @author Dmitry S
 */
enum HttpMethods: string
{
    case METHOD_HEAD = 'HEAD';
    case METHOD_GET = 'GET';
    case METHOD_POST = 'POST';
    case METHOD_PUT = 'PUT';
    case METHOD_PATCH = 'PATCH';
    case METHOD_DELETE = 'DELETE';
    case METHOD_PURGE = 'PURGE';
    case METHOD_OPTIONS = 'OPTIONS';
    case METHOD_TRACE = 'TRACE';
    case METHOD_CONNECT = 'CONNECT';
}
