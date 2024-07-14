<?php

namespace KeywordsFinder\Service\Request;

/**
 * Either defines logic for the {@see GuzzleService} or contains of logic which prevents bloating the main service
 */
interface GuzzleServiceInterface
{

    public const KEY_RAW_REQUEST_BODY  = "body";
    public const KEY_JSON_REQUEST_BODY = "json";
    public const KEY_HEADERS           = "headers";

    public const HEADER_AUTH_STRATEGY = "auth-strategy";
    public const HEADER_CONTENT_TYPE  = "Content-Type";

    public const APPLICATION_JSON   = "application/json";
    public const AUTH_STRATEGY_ANON = "Anon";

}