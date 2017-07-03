<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;
use Mailgun\Constants\ExceptionMessages;
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
            if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
                $this->responseBody['message'] = $body;
            } else {
                $this->responseBody = json_decode($body, true);
            }
        }
    }

    public static function badRequest(ResponseInterface $response = null)
    {
        return new self(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_PARAMETERS, 400, $response);
    }

    public static function unauthorized(ResponseInterface $response = null)
    {
        return new self(ExceptionMessages::EXCEPTION_INVALID_CREDENTIALS, 401, $response);
    }

    public static function requestFailed(ResponseInterface $response = null)
    {
        return new self(ExceptionMessages::VALID_PARAMETERS_FAILED, 402, $response);
    }

    public static function notFound(ResponseInterface $response = null)
    {
        return new self(ExceptionMessages::EXCEPTION_MISSING_ENDPOINT, 404, $response);
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
