<?php

namespace Classid\SsoDriver\Traits;

use Classid\SsoDriver\Enums\ResponseCode;
use Classid\SsoDriver\Exceptions\SSODriverException;
use Classid\SsoDriver\Exceptions\UnknownErrorHandlerException;

trait SSOServiceErrorHandler
{
    /**
     * @param array|null $errorResponse
     * @return void
     * @throws UnknownErrorHandlerException
     */
    public static function unknownResponseErrorHandler(?array $errorResponse): void
    {
        if (
            is_null($errorResponse) ||
            !isset($errorResponse["rc"]) ||
            $errorResponse["rc"] === ResponseCode::ERR_UNKNOWN ||
            !ResponseCode::tryFromName($errorResponse["rc"])
        ) { //condition when error response null, rc null (mean exception from sso is not mapped yet), or response code does not exists
            throw new UnknownErrorHandlerException($errorResponse["message"] ?? "Something went wrong on sso auth");
        }
    }

    /**
     * @param array|null $errorResponse
     * @return void
     * @throws SSODriverException
     */
    public static function mappingErrorHandler(?array $errorResponse): void
    {
        throw new SSODriverException($errorResponse["message"], $errorResponse);
    }
}
