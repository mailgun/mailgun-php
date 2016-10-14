<?php

namespace Mailgun\HttpClient;

use Mailgun\Exception\HttpClientException;
use Mailgun\Exception\HttpServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Contributors of https://github.com/KnpLabs/php-github-api
 */
class ResponseMediator
{
    /**
     * @param ResponseInterface $response
     *
     * @return array|string
     */
    public static function getContent(ResponseInterface $response)
    {
        self::verifyResponse($response);

        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return $content;
            }
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     */
    private static function verifyResponse(ResponseInterface $response)
    {
        $httpStatus = $response->getStatusCode();
        switch ($httpStatus) {
            case 200:
                return;
            case 400:
                throw HttpClientException::badRequest();
            case 401:
                throw HttpClientException::unauthorized();
            case 402:
                throw HttpClientException::requestFailed();
            case 404:
                throw HttpClientException::notFound();
            case 500:
            case 502:
            case 503:
            case 504:
                throw HttpServerException::serverError($httpStatus);
            default:
                throw HttpServerException::unknownHttpResponseCode($httpStatus);
        }
    }
}
