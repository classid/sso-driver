<?php

namespace Classid\SsoDriver\Traits;

use App\Enums\ResponseCode;
use App\Exceptions\CidException;

trait SSOServiceErrorHandler
{
    /**
     * @param array|null $errorResponse
     * @return void
     * @throws CidException
     */
    public static function unknownResponseErrorHandler(?array $errorResponse): void
    {
        if (is_null($errorResponse) || !isset($errorResponse["rc"]) || !ResponseCode::tryFromName($errorResponse["rc"] || $errorResponse["rc"] === ResponseCode::ERR_UNKNOWN)) { //condition when error response null, rc null (mean exception from sso is not mapped yet), or response code does not exists
            throw new CidException(ResponseCode::ERR_UNKNOWN, "Something went wrong on sso auth");
        }
    }

    /**
     * @param array|null $errorResponse
     * @return void
     * @throws CidException
     */
    public static function mappingErrorHandler(?array $errorResponse): void
    {
        throw new CidException(ResponseCode::tryFromName($errorResponse["rc"]), $errorResponse["message"]);
    }
}
