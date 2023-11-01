<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Host
    |--------------------------------------------------------------------------
    |
    | This is host of sso server
    |
    */
    "host" => env("MUMTAZ_SSO_CLIENT_HOST"),


    /*
    |--------------------------------------------------------------------------
    | Client Id
    |--------------------------------------------------------------------------
    |
    | This is client id. As a client, you must be registered on sso and use this
    | client id as identifier
    |
    */
    "client_id" => env("MUMTAZ_SSO_CLIENT_ID"),

    /*
    |--------------------------------------------------------------------------
    | Client Secret
    |--------------------------------------------------------------------------
    |
    | This is client secret. Client secret use for validate credential identifier
    | when access sso resource
    |
    */
    "client_secret" => env("MUMTAZ_SSO_CLIENT_SECRET"),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This is api key using for validate signature callback from sso to client
    |
    */
    "api_key" => env("MUMTAZ_SSO_CLIENT_API_KEY", null),
];
