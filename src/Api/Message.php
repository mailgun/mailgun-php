<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Exception;
use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Message\BatchMessage;
use Mailgun\Model\Message\QueueStatusResponse;
use Mailgun\Model\Message\SendResponse;
use Mailgun\Model\Message\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * @see https://documentation.mailgun.com/en/latest/api-sending.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Message extends HttpApi
{
    /**
     * @param  string       $domain
     * @param  bool         $autoSend
     * @return BatchMessage
     */
    public function getBatchMessage(string $domain, bool $autoSend = true): BatchMessage
    {
        return new BatchMessage($this, $domain, $autoSend);
    }

    /**
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#sending
     * @param  string                         $domain
     * @param  array                          $params
     * @param  array                          $requestHeaders
     * @return SendResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function send(string $domain, array $params, array $requestHeaders = [])
    {
        Assert::string($domain);
        Assert::notEmpty($domain);
        Assert::notEmpty($params);

        $postDataMultipart = [];
        $fields = ['attachment', 'inline'];
        foreach ($fields as $fieldName) {
            if (!isset($params[$fieldName])) {
                continue;
            }

            Assert::isArray($params[$fieldName]);
            foreach ($params[$fieldName] as $file) {
                $postDataMultipart[] = $this->prepareFile($fieldName, $file);
            }

            unset($params[$fieldName]);
        }

        $postDataMultipart = array_merge($this->prepareMultipartParameters($params), $postDataMultipart);
        try {
            $response = $this->httpPostRaw(sprintf('/v3/%s/messages', $domain), $postDataMultipart, $requestHeaders);
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        } finally {
            $this->closeResources($postDataMultipart);
        }

        return $this->hydrateResponse($response, SendResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#sending
     * @param  string                         $domain
     * @param  array                          $recipients     with all you send emails to. Including bcc and cc
     * @param  string                         $message        Message filepath or content
     * @param  array                          $params
     * @param  array                          $requestHeaders
     * @return SendResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function sendMime(string $domain, array $recipients, string $message, array $params, array $requestHeaders = [])
    {
        Assert::string($domain);
        Assert::notEmpty($domain);
        Assert::notEmpty($recipients);
        Assert::notEmpty($message);
        Assert::nullOrIsArray($params);

        $params['to'] = $recipients;
        $postDataMultipart = $this->prepareMultipartParameters($params);

        if (strlen($message) < PHP_MAXPATHLEN && is_file($message)) {
            $fileData = ['filePath' => $message];
        } else {
            $fileData = [
                'fileContent' => $message,
                'filename' => 'message',
            ];
        }
        $postDataMultipart[] = $this->prepareFile('message', $fileData);
        try {
            $response = $this->httpPostRaw(sprintf('/v3/%s/messages.mime', $domain), $postDataMultipart, $requestHeaders);
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        } finally {
            $this->closeResources($postDataMultipart);
        }

        return $this->hydrateResponse($response, SendResponse::class);
    }

    /**
     * Get stored message.
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#retrieving-stored-messages
     * @param  string                         $url
     * @param  bool                           $rawMessage     if true we will use "Accept: message/rfc2822" header
     * @param  array                          $requestHeaders
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $url, bool $rawMessage = false, array $requestHeaders = [])
    {
        Assert::notEmpty($url);

        $headers = [];
        if ($rawMessage) {
            $headers['Accept'] = 'message/rfc2822';
        }
        if (!empty($requestHeaders)) {
            $headers = array_merge($headers, $requestHeaders);
        }

        $response = $this->httpGet($url, [], $headers);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Get messages queue status
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/openapi-final/tag/Messages/#tag/Messages/operation/httpapi.(*LegacyHttpApi).GetDomainSendingQueues-fm-70
     * @param string $domain
     * @param array $requestHeaders
     * @return QueueStatusResponse
     * @throws ClientExceptionInterface
     */
    public function getMessageQueueStatus(string $domain, array $requestHeaders = [])
    {
        Assert::notEmpty($domain);
        $response = $this->httpGet(sprintf('/v3/domains/%s/sending_queues', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, QueueStatusResponse::class);
    }

    /**
     * @param string $domain
     * @param string $storageId
     * @param array $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function retrieveStoredMessage(string $domain, string $storageId, array $requestHeaders = []): ShowResponse
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($storageId);
        $response = $this->httpGet(sprintf('/v3/domains/%s/messages/%s', $domain, $storageId), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param array $filePath array('fileContent' => 'content') or array('filePath' => '/foo/bar')
     *
     * @throws InvalidArgumentException
     */
    private function prepareFile(string $fieldName, array $filePath): array
    {
        $filename = $filePath['filename'] ?? null;

        if (isset($filePath['fileContent'])) {
            // File from memory
            $resource = fopen('php://temp', 'rb+');
            fwrite($resource, $filePath['fileContent']);
            rewind($resource);
        } elseif (isset($filePath['filePath'])) {
            // File form path
            $path = $filePath['filePath'];

            // Remove leading @ symbol
            if (0 === strpos($path, '@')) {
                $path = substr($path, 1);
            }

            $resource = fopen($path, 'rb');
        } else {
            throw new InvalidArgumentException('When using a file you need to specify parameter "fileContent" or "filePath"');
        }

        return [
            'name' => $fieldName,
            'content' => $resource,
            'filename' => $filename,
        ];
    }

    /**
     * Prepare multipart parameters. Make sure each POST parameter is split into an array with 'name' and 'content' keys.
     */
    private function prepareMultipartParameters(array $params): array
    {
        $postDataMultipart = [];
        foreach ($params as $key => $value) {
            // If $value is not an array we cast it to an array
            foreach ((array) $value as $subValue) {
                if (is_int($subValue)) {
                    $subValue = (string) $subValue;
                }
                $postDataMultipart[] = [
                    'name' => $key,
                    'content' => $subValue,
                ];
            }
        }

        return $postDataMultipart;
    }

    /**
     * Close open resources.
     */
    private function closeResources(array $params): void
    {
        foreach ($params as $param) {
            if (is_array($param) && array_key_exists('content', $param) && is_resource($param['content'])) {
                fclose($param['content']);
            }
        }
    }
}
