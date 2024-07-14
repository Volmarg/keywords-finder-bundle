<?php

namespace KeywordsFinder\Controller;

use KeywordsFinder\Service\Keywords\ServiceInterface;

/**
 * Returns keywords from all keywords services
 */
class KeywordsController
{
    /**
     * @var ServiceInterface[] $keywordServices
     */
    private array $keywordServices = [];

    /**
     * @param ServiceInterface[] $keywordServices
     */
    public function setKeywordServices(array $keywordServices): void
    {
        $this->keywordServices = $keywordServices;
    }

    /**
     * Will return array of keywords for text
     *
     * @param string $text
     * @return array
     */
    public function get(string $text): array
    {
        $matchingKeywords = [];
        foreach($this->keywordServices as $keywordService){
            $matchingKeywords = array_merge($matchingKeywords, $keywordService->get($text));
        }

        // leave only unique and reset the indexed key
        $uniqueKeywords = array_values(array_unique($matchingKeywords));
        return $uniqueKeywords;
    }
}