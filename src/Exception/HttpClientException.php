<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class HttpClientException extends \RuntimeException implements Exception
{
    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var array
     */
    private $responseBody = [];

    /**
     * @var int
     */
    private $responseCode;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->response = $response;
        $this->responseCode = $response->getStatusCode();
        $body = $response->getBody()->__toString();
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $this->responseBody['message'] = $body;
        } else {
            $this->responseBody = json_decode($body, true);
        }
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function badRequest(ResponseInterface $response): HttpClientException
    {
        $body = $response->getBody()->__toString();
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;
        }

        $message = sprintf("The parameters passed to the API were invalid. Check your inputs!\n\n%s", $validationMessage);

        return new self($message, 400, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function unauthorized(ResponseInterface $response): HttpClientException
    {
        return new self('Your credentials are incorrect.', 401, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function requestFailed(ResponseInterface $response): HttpClientException
    {
        return new self('Parameters were valid but request failed. Try again.', 402, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function notFound(ResponseInterface $response): HttpClientException
    {
        $serverMessage = [];
        $defaultMessage = 'The endpoint you have tried to access does not exist. Check if the domain matches the domain you have configure on Mailgun.';
        try {
            $serverMessage = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $throwable) {
        }

        return new self($serverMessage['message'] ?? $defaultMessage, 404, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function conflict(ResponseInterface $response): HttpClientException
    {
        return new self('Request conflicts with current state of the target resource.', 409, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function payloadTooLarge(ResponseInterface $response): HttpClientException
    {
        return new self('Payload too large, your total attachment size is too big.', 413, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function tooManyRequests(ResponseInterface $response): HttpClientException
    {
        return new self('Too many requests.', 429, $response);
    }

    /**
     * @param  ResponseInterface   $response
     * @return HttpClientException
     */
    public static function forbidden(ResponseInterface $response): HttpClientException
    {
        $body = $response->getBody()->__toString();
        if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            $validationMessage = $body;
        } else {
            $jsonDecoded = json_decode($body, true);
            $validationMessage = isset($jsonDecoded['Error']) ? $jsonDecoded['Error'] : $body;
        }

        $message = sprintf("Forbidden!\n\n%s", $validationMessage);

        return new self($message, 403, $response);
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
