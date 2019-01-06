<?php

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

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Message extends HttpApi
{
    /**
     * @param string $domain
     * @param bool   $autoSend
     *
     * @return BatchMessage
     */
    public function getBatchMessage($domain, $autoSend = true)
    {
        return new BatchMessage($this, $domain, $autoSend);
    }

    /**
     * @param string $domain
     * @param array  $params
     *
     * @return SendResponse
     */
    public function send($domain, array $params)
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
     * @param string $domain
     * @param array  $recipients with all you send emails to. Including bcc and cc
     * @param string $message    Message filepath or content
     * @param array  $params
     */
    public function sendMime($domain, array $recipients, $message, array $params)
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
     * @param string $url
     * @param bool   $rawMessage if true we will use "Accept: message/rfc2822" header
     *
     * @return ShowResponse
     */
    public function show($url, $rawMessage = false)
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
     * Prepare a file.
     *
     * @param string $fieldName
     * @param array  $filePath  array('fileContent' => 'content') or array('filePath' => '/foo/bar')
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function prepareFile($fieldName, array $filePath)
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
     *
     * @param array $params
     *
     * @return array
     */
    private function prepareMultipartParameters(array $params)
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
     *
     * @param array $params
     */
    private function closeResources(array $params)
    {
        foreach ($params as $param) {
            if (is_array($param) && array_key_exists('content', $param) && is_resource($param['content'])) {
                fclose($param['content']);
            }
        }
    }
}
