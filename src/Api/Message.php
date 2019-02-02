<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Message\BatchMessage;
use Mailgun\Model\Message\SendResponse;
use Mailgun\Model\Message\ShowResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-sending.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Message extends HttpApi
{
    public function getBatchMessage(string $domain, bool $autoSend = true): BatchMessage
    {
        return new BatchMessage($this, $domain, $autoSend);
    }

    /**
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#sending
     *
     * @return SendResponse|ResponseInterface
     */
    public function send(string $domain, array $params)
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
        $response = $this->httpPostRaw(sprintf('/v3/%s/messages', $domain), $postDataMultipart);
        $this->closeResources($postDataMultipart);

        return $this->hydrateResponse($response, SendResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#sending
     *
     * @param array  $recipients with all you send emails to. Including bcc and cc
     * @param string $message    Message filepath or content
     *
     * @return SendResponse|ResponseInterface
     */
    public function sendMime(string $domain, array $recipients, string  $message, array $params)
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
        $response = $this->httpPostRaw(sprintf('/v3/%s/messages.mime', $domain), $postDataMultipart);
        $this->closeResources($postDataMultipart);

        return $this->hydrateResponse($response, SendResponse::class);
    }

    /**
     * Get stored message.
     *
     * @see https://documentation.mailgun.com/en/latest/api-sending.html#retrieving-stored-messages
     *
     * @param bool $rawMessage if true we will use "Accept: message/rfc2822" header
     *
     * @return ShowResponse|ResponseInterface
     */
    public function show(string $url, bool $rawMessage = false)
    {
        Assert::notEmpty($url);

        $headers = [];
        if ($rawMessage) {
            $headers['Accept'] = 'message/rfc2822';
        }

        $response = $this->httpGet($url, [], $headers);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param array $filePath array('fileContent' => 'content') or array('filePath' => '/foo/bar')
     *
     * @throws InvalidArgumentException
     */
    private function prepareFile(string $fieldName, array $filePath): array
    {
        $filename = isset($filePath['filename']) ? $filePath['filename'] : null;

        if (isset($filePath['fileContent'])) {
            // File from memory
            $resource = fopen('php://temp', 'r+');
            fwrite($resource, $filePath['fileContent']);
            rewind($resource);
        } elseif (isset($filePath['filePath'])) {
            // File form path
            $path = $filePath['filePath'];

            // Remove leading @ symbol
            if (0 === strpos($path, '@')) {
                $path = substr($path, 1);
            }

            $resource = fopen($path, 'r');
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
