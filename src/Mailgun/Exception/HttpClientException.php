<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;
use Psr\Http\Message\ResponseInterface;

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
    private $responseBody;

    /**
     * @var int
     */
    private $responseCode;

    /**
     * @param string                 $message
     * @param int                    $code
     * @param ResponseInterface|null $response
     */
    public function __construct($message, $code, ResponseInterface $response = null)
    {
        parent::__construct($message, $code);

        if ($response) {
            $this->response = $response;
            $this->responseCode = $response->getStatusCode();
            $body = $response->getBody()->__toString();
            if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
                $this->responseBody['message'] = $body;
            } else {
                $this->responseBody = json_decode($body, true);
            }
        }
    }

    public static function badRequest(ResponseInterface $response = null)
    {
        $message = 'The parameters passed to the API were invalid. Check your inputs!';

        if (null !== $response) {
            $body = $response->getBody()->__toString();
            if (0 !== strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
                $validationMessage = $body;
            } else {
                $jsonDecoded = json_decode($body, true);
                $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;
            }

            $message = sprintf("%s\n\n%s", $message, $validationMessage);
        }

        return new self($message, 400, $response);
    }

    public static function unauthorized(ResponseInterface $response = null)
    {
        return new self('Your credentials are incorrect.', 401, $response);
    }

    public static function requestFailed(ResponseInterface $response = null)
    {
        return new self('Parameters were valid but request failed. Try again.', 402, $response);
    }

    public static function notFound(ResponseInterface $response = null)
    {
        return new self('The endpoint you have tried to access does not exist. Check if the domain matches the domain you have configure on Mailgun.', 404, $response);
    }

    public static function payloadTooLarge(ResponseInterface $response = null)
    {
        return new self('Payload too large, your total attachment size is too big.', 413, $response);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
}
