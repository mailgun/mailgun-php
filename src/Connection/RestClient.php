<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Connection;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Constants\Api;
use Mailgun\Constants\ExceptionMessages;
use Psr\Http\Message\ResponseInterface;

/**
 * This class is a wrapper for the HTTP client.
 *
 * @deprecated Will be removed in 3.0
 */
class RestClient
{
    /**
     * Your API key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiHost;

    /**
     * The version of the API to use.
     *
     * @var string
     */
    protected $apiVersion = 'v2';

    /**
     * If we should use SSL or not.
     *
     * @var bool
     *
     * @deprecated To be removed in 3.0
     */
    protected $sslEnabled = true;

    /**
     * @param string     $apiKey
     * @param string     $apiHost
     * @param HttpClient $httpClient
     */
    public function __construct($apiKey, $apiHost, HttpClient $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param mixed  $body
     * @param array  $files
     * @param array  $headers
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    protected function send($method, $uri, $body = null, $files = [], array $headers = [])
    {
        $headers['User-Agent'] = Api::SDK_USER_AGENT.'/'.Api::SDK_VERSION;
        $headers['Authorization'] = 'Basic '.base64_encode(sprintf('%s:%s', Api::API_USER, $this->apiKey));

        if (!empty($files)) {
            $builder = new MultipartStreamBuilder();
            foreach ($files as $file) {
                $builder->addResource($file['name'], $file['contents'], $file);
            }
            $body = $builder->build();
            $headers['Content-Type'] = 'multipart/form-data; boundary="'.$builder->getBoundary().'"';
        } elseif (is_array($body)) {
            $body = http_build_query($body);
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $request = MessageFactoryDiscovery::find()->createRequest($method, $this->getApiUrl($uri), $headers, $body);
        $response = $this->getHttpClient()->sendRequest($request);

        return $this->responseHandler($response);
    }

    /**
     * @param string $url
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function getAttachment($url)
    {
        $headers['User-Agent'] = Api::SDK_USER_AGENT.'/'.Api::SDK_VERSION;
        $headers['Authorization'] = 'Basic '.base64_encode(sprintf('%s:%s', Api::API_USER, $this->apiKey));
        $request = MessageFactoryDiscovery::find()->createRequest('get', $url, $headers);
        $response = HttpClientDiscovery::find()->sendRequest($request);

        return $this->responseHandler($response);
    }

    /**
     * @param string $endpointUrl
     * @param array  $postData
     * @param array  $files
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function post($endpointUrl, array $postData = [], $files = [])
    {
        $postFiles = [];

        $fields = ['message', 'attachment', 'inline'];
        foreach ($fields as $fieldName) {
            if (isset($files[$fieldName])) {
                if (is_array($files[$fieldName])) {
                    foreach ($files[$fieldName] as $file) {
                        $postFiles[] = $this->prepareFile($fieldName, $file);
                    }
                } else {
                    $postFiles[] = $this->prepareFile($fieldName, $files[$fieldName]);
                }
            }
        }

        $postDataMultipart = [];
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subValue) {
                    $postDataMultipart[] = [
                        'name' => $key,
                        'contents' => $subValue,
                    ];
                }
            } else {
                $postDataMultipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        return $this->send('POST', $endpointUrl, [], array_merge($postDataMultipart, $postFiles));
    }

    /**
     * @param string $endpointUrl
     * @param array  $queryString
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function get($endpointUrl, $queryString = [])
    {
        return $this->send('GET', $endpointUrl.'?'.http_build_query($queryString));
    }

    /**
     * @param string $endpointUrl
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function delete($endpointUrl)
    {
        return $this->send('DELETE', $endpointUrl);
    }

    /**
     * @param string $endpointUrl
     * @param mixed  $putData
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function put($endpointUrl, $putData)
    {
        return $this->send('PUT', $endpointUrl, $putData);
    }

    /**
     * @param ResponseInterface $responseObj
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function responseHandler(ResponseInterface $responseObj)
    {
        $httpResponseCode = (int) $responseObj->getStatusCode();

        switch ($httpResponseCode) {
        case 200:
            $data = (string) $responseObj->getBody();
            $jsonResponseData = json_decode($data, false);
            $result = new \stdClass();
            // return response data as json if possible, raw if not
            $result->http_response_body = $data && null === $jsonResponseData ? $data : $jsonResponseData;
            $result->http_response_code = $httpResponseCode;

            return $result;
        case 400:
            throw new MissingRequiredParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_PARAMETERS.$this->getResponseExceptionMessage($responseObj));
        case 401:
            throw new InvalidCredentials(ExceptionMessages::EXCEPTION_INVALID_CREDENTIALS);
        case 404:
            throw new MissingEndpoint(ExceptionMessages::EXCEPTION_MISSING_ENDPOINT.$this->getResponseExceptionMessage($responseObj));
        default:
            throw new GenericHTTPError(ExceptionMessages::EXCEPTION_GENERIC_HTTP_ERROR, $httpResponseCode, $responseObj->getBody());
        }
    }

    /**
     * @param ResponseInterface $responseObj
     *
     * @return string
     */
    protected function getResponseExceptionMessage(ResponseInterface $responseObj)
    {
        $body = (string) $responseObj->getBody();
        $response = json_decode($body);
        if (JSON_ERROR_NONE == json_last_error() && isset($response->message)) {
            return ' '.$response->message;
        }

        return '';
    }

    /**
     * Prepare a file for the postBody.
     *
     * @param string       $fieldName
     * @param string|array $filePath
     *
     * @return array
     */
    protected function prepareFile($fieldName, $filePath)
    {
        $filename = null;

        if (is_array($filePath) && isset($filePath['fileContent'])) {
            // File from memory
            $filename = $filePath['filename'];
            $resource = fopen('php://temp', 'r+');
            fwrite($resource, $filePath['fileContent']);
            rewind($resource);
        } else {
            // Backward compatibility code
            if (is_array($filePath) && isset($filePath['filePath'])) {
                $filename = $filePath['remoteName'];
                $filePath = $filePath['filePath'];
            }

            // Remove leading @ symbol
            if (0 === strpos($filePath, '@')) {
                $filePath = substr($filePath, 1);
            }

            $resource = fopen($filePath, 'r');
        }

        return [
            'name' => $fieldName,
            'contents' => $resource,
            'filename' => $filename,
        ];
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    private function getApiUrl($uri)
    {
        return $this->generateEndpoint($this->apiHost, $this->apiVersion, $this->sslEnabled).$uri;
    }

    /**
     * @param string $apiEndpoint
     * @param string $apiVersion
     * @param bool   $ssl
     *
     * @return string
     */
    private function generateEndpoint($apiEndpoint, $apiVersion, $ssl)
    {
        return ($ssl ? 'https://' : 'http://').$apiEndpoint.'/'.$apiVersion.'/';
    }

    /**
     * @param string $apiVersion
     *
     * @return RestClient
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @param bool $sslEnabled
     *
     * @return RestClient
     *
     * @deprecated To be removed in 3.0
     */
    public function setSslEnabled($sslEnabled)
    {
        $this->sslEnabled = $sslEnabled;

        return $this;
    }
}
