<?php

declare(strict_types=1);

namespace Scott\GeminiPhp;

use RuntimeException;
use Scott\GeminiPhp\Contract\RequestContract;
use Scott\GeminiPhp\Enum\GeminiModel;
use Scott\GeminiPhp\Request\CountToken;
use Scott\GeminiPhp\Request\GenerateData;
use Scott\GeminiPhp\Request\ModelList;
use Scott\GeminiPhp\Response\ContentResponse;
use Scott\GeminiPhp\Response\ModelResponse;
use Scott\GeminiPhp\Response\TokenResponse;

class Gemini
{
    protected const API_KEY_HEADER_NAME = 'x-goog-api-key';

    protected string $baseUrl = 'https://generativelanguage.googleapis.com';

    public function __construct(
        protected string                   $apiToken,
        protected ?HttpClientInterface     $client = null,
        protected ?RequestFactoryInterface $requestFactory = null,
        protected ?StreamFactoryInterface  $streamFactory = null,
    )
    {
    }

    public static function token(string $apiToken): self
    {
        return new self($apiToken);
    }

    public function geminiPro(): GenerativeModel
    {
        return $this->generativeModel(GeminiModel::PRO);
    }

    protected function generativeModel(GeminiModel $modelName): GenerativeModel
    {
        return new GenerativeModel(
            $this,
            $modelName,
        );
    }

    public function geminiProVision(): GenerativeModel
    {
        return $this->generativeModel(GeminiModel::PRO_VISION);
    }

    public function generateContent(GenerateData $request): ContentResponse
    {
        $response = $this->makeRequest($request);
        $json = json_decode($response, associative: true);

        return ContentResponse::fromArray($json);
    }

    protected function makeRequest(RequestContract $request): string
    {
        if (!isset($this->client, $this->requestFactory, $this->streamFactory)) {
            throw new RuntimeException('Missing client or factory for Gemini API operation');
        }

        $uri = "{$this->baseUrl}/v1/{$request->getOperation()}";
        $httpRequest = $this->requestFactory
            ->createRequest($request->getHttpMethod(), $uri)
            ->withAddedHeader(self::API_KEY_HEADER_NAME, $this->apiToken);

        $payload = $request->getHttpPayload();
        if (!empty($payload)) {
            $stream = $this->streamFactory->createStream($payload);
            $httpRequest = $httpRequest->withBody($stream);
        }

        $response = $this->client->sendRequest($httpRequest);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(
                sprintf(
                    'Gemini API operation failed: operation=%s, status_code=%d,  response=%s',
                    $request->getOperation(),
                    $response->getStatusCode(),
                    $response->getBody(),
                ),
            );
        }

        return (string)$response->getBody();
    }

    public function countTokens(CountToken $request): TokenResponse
    {
        $response = $this->makeRequest($request);
        $json = json_decode($response, true);

        return TokenResponse::fromArray($json);
    }

    public function listModels(): ModelResponse
    {
        $request = new ModelList();
        $response = $this->makeRequest($request);
        $json = json_decode($response, associative: true);

        return ModelResponse::fromArray($json);
    }

    public function withBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

}
