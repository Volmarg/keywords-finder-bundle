<?php

namespace KeywordsFinder\Service\Keywords;

use Exception;
use KeywordsFinder\Service\LoggerService;
use KeywordsFinder\Service\Request\GuzzleService;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Abstraction layer for the keywords services
 */
abstract class AbstractKeywordsService
{

    /**
     * @return string
     */
    abstract public function getApiUrl(): string;

    /**
     * @var GuzzleService $guzzleService
     */
    private GuzzleService $guzzleService;

    /**
     * @var LoggerService $loggerService
     */
    protected LoggerService $loggerService;

    /**
     * @param GuzzleService $guzzleService
     * @param LoggerService $loggerService
     */
    public function __construct(GuzzleService $guzzleService, LoggerService $loggerService)
    {
        $this->loggerService = $loggerService;
        $this->guzzleService = $guzzleService;
    }

    /**
     * Will handle the request to obtain the keywords from the external service
     * This handling logic is being added explicitly for the keywords services
     * and should return eventually empty array if there are some connection issues
     *
     * @param string $requestMethod
     * @param array  $requestOptions
     *
     * @return array
     * @throws Exception
     */
    protected function getResponseData(string $requestMethod, array $requestOptions = []): array
    {

        /** @var ResponseInterface $response */
        $callableRequestMethodName = strtolower($requestMethod);
        try{
            $response = $this->guzzleService->{$callableRequestMethodName}($this->getApiUrl(), $requestOptions);
        }catch(Exception $e){
            $this->loggerService->logException($e);
            return [];
        }

        $responseData = json_decode($response->getBody()->getContents(), true);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $this->loggerService->critical("Something is wrong with response, got json error", [
                "error" => json_last_error_msg(),
            ]);
            return [];
        }

        return $responseData;
    }

}