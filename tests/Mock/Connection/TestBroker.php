<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Mock\Connection;

use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Connection\RestClient;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;

class TestBroker extends RestClient
{
    private $apiKey;

    protected $apiEndpoint;

    public function __construct($apiKey = null, $apiHost = 'api.mailgun.net', $apiVersion = 'v3')
    {
        $this->apiKey = $apiKey;
        $this->apiEndpoint = $apiHost;
    }

    public function post($endpointUrl, array $postData = [], $files = [])
    {
        return $this->testResponseHandler($endpointUrl, $httpResponseCode = 200);
    }

    public function get($endpointUrl, $queryString = [])
    {
        return $this->testResponseHandler($endpointUrl, $httpResponseCode = 200);
    }

    public function delete($endpointUrl)
    {
        return $this->testResponseHandler($endpointUrl, $httpResponseCode = 200);
    }

    public function put($endpointUrl, $queryString)
    {
        return $this->testResponseHandler($endpointUrl, $httpResponseCode = 200);
    }

    public function testResponseHandler($endpointUrl, $httpResponseCode = 200)
    {
        if (200 === $httpResponseCode) {
            $result = new \stdClass();
            $result->http_response_body = new \stdClass();
            $jsonResponseData = json_decode('{"message": "Some JSON Response Data", "id": "1234"}');
            foreach ($jsonResponseData as $key => $value) {
                $result->http_response_body->$key = $value;
            }
        } elseif (400 == $httpResponseCode) {
            throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        } elseif (401 == $httpResponseCode) {
            throw new InvalidCredentials(EXCEPTION_INVALID_CREDENTIALS);
        } elseif (401 == $httpResponseCode) {
            throw new GenericHTTPError(EXCEPTION_INVALID_CREDENTIALS);
        } elseif (404 == $httpResponseCode) {
            throw new MissingEndpoint(EXCEPTION_MISSING_ENDPOINT);
        } else {
            throw new GenericHTTPError(EXCEPTION_GENERIC_HTTP_ERROR);
            return false;
        }
        $result->http_response_code = $httpResponseCode;
        $result->http_endpoint_url = $endpointUrl;

        return $result;
    }
}
