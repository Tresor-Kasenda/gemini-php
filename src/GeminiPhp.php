<?php

declare(strict_types=1);

namespace Scott\GeminiPhp;

use RuntimeException;

class GeminiPhp
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
        return $this->generativeModel(ModelName::GeminiPro);
    }

    protected function generativeModel(ModelName $modelName): GenerativeModel
    {
        return new GenerativeModel(
            $this,
            $modelName,
        );
    }

    public function geminiProVision(): GenerativeModel
    {
        return $this->generativeModel(ModelName::GeminiProVision);
    }

    public function generateContent(GenerateContentRequest $request): GenerateContentResponse
    {
        $response = $this->doRequest($request);
        $json = json_decode($response, associative: true);

        return GenerateContentResponse::fromArray($json);
    }

    protected function doRequest(RequestInterface $request): string
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

    public function countTokens(CountTokensRequest $request): CountTokensResponse
    {
        $response = $this->doRequest($request);
        $json = json_decode($response, true);

        return CountTokensResponse::fromArray($json);
    }

    public function listModels(): ListModelsResponse
    {
        $request = new ListModelsRequest();
        $response = $this->doRequest($request);
        $json = json_decode($response, associative: true);

        return ListModelsResponse::fromArray($json);
    }

    public function withBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

}
