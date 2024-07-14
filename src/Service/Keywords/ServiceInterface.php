<?php

namespace KeywordsFinder\Service\Keywords;

use KeywordsFinder\Service\LoggerService;
use KeywordsFinder\Service\Request\GuzzleService;

/**
 * Defines common logic for services for keywords
 */
interface ServiceInterface
{
    /**
     * @param string        $apiUrl
     * @param GuzzleService $client
     * @param LoggerService $loggerService
     */
    public function __construct(string $apiUrl, GuzzleService $client, LoggerService $loggerService);

    /**
     * This method returns array of keywords for provided string
     *
     * @param string $text
     * @return array
     */
    public function get(string $text): array;

}