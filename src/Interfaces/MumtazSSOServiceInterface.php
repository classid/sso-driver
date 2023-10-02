<?php

namespace Classid\SsoDriver\Interfaces;


use Classid\SsoDriver\Abstracts\BaseMumtazSSOService;

interface MumtazSSOServiceInterface
{
    public function getClientAccessToken():string;

    public function addHeaders(array $headers):BaseMumtazSSOService;
    public function getHeaders():array;
    public function getBaseUrl():string;
}
