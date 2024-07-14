<?php

namespace KeywordsFinder\Service\Keywords;

use Exception;
use KeywordsFinder\Service\LoggerService;
use KeywordsFinder\Service\Request\GuzzleService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Self-hosted solution based on {@link https://github.com/MaartenGr/KeyBERT}
 */
class KeyBertService extends AbstractKeywordsService implements ServiceInterface
{
    private const MAIN_KEY_KEYWORDS = "keywords";

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @var string $apiUrl
     */
    private string $apiUrl;

    /**
     * @param string        $apiUrl
     * @param GuzzleService $guzzleService
     * @param LoggerService $loggerService
     */
    public function __construct(string $apiUrl, GuzzleService $guzzleService, LoggerService $loggerService)
    {
        parent::__construct($guzzleService, $loggerService);
        $this->apiUrl = $apiUrl;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $text
     *
     * @return array
     * @throws Exception
     */
    public function get(string $text): array
    {
        $guzzleOptions = [
            'form_params' => [
                'text' => $text,
            ],
        ];

        $responseData = $this->getResponseData(Request::METHOD_POST, $guzzleOptions);
        if (!array_key_exists(self::MAIN_KEY_KEYWORDS, $responseData)) {
            throw new Exception("Response does not contain key: " . self::MAIN_KEY_KEYWORDS);
        }

        $keywordsInfo = $responseData[self::MAIN_KEY_KEYWORDS];
        $keywords     = [];
        foreach ($keywordsInfo as $keywordInfo) {
            $keyword = $keywordInfo[array_key_first($keywordInfo)];
            if (!is_string($keyword)) {
                throw new Exception("Keyword is not a string, got: " . json_encode($keyword));
            }

            $keywords[] = $keyword;
        }

        return $keywords;
    }
}