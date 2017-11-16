<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Messages;

use Mailgun\Constants\Api;
use Mailgun\Constants\ExceptionMessages;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Messages\Exceptions\TooManyParameters;

/**
 * This class is used for batch sending. See the official documentation (link below)
 * for usage instructions.
 *
 * @see https://github.com/mailgun/mailgun-php/blob/master/src/Mailgun/Messages/README.md
 */
class BatchMessage extends MessageBuilder
{
    /**
     * @var array
     */
    private $batchRecipientAttributes;

    /**
     * @var bool
     */
    private $autoSend;

    /**
     * @var \Mailgun\Connection\RestClient
     */
    private $restClient;

    /**
     * @var string
     */
    private $workingDomain;

    /**
     * @var array
     */
    private $messageIds = [];

    /**
     * @var string
     */
    private $endpointUrl;

    /**
     * @param \Mailgun\Connection\RestClient $restClient
     * @param string                         $workingDomain
     * @param bool                           $autoSend
     */
    public function __construct($restClient, $workingDomain, $autoSend)
    {
        $this->batchRecipientAttributes = [];
        $this->autoSend = $autoSend;
        $this->restClient = $restClient;
        $this->workingDomain = $workingDomain;
        $this->endpointUrl = $workingDomain.'/messages';
    }

    /**
     * @param string $headerName
     * @param string $address
     * @param array  $variables
     *
     * @throws MissingRequiredMIMEParameters
     * @throws TooManyParameters
     */
    protected function addRecipient($headerName, $address, $variables)
    {
        if (array_key_exists($headerName, $this->counters['recipients'])) {
            if ($this->counters['recipients'][$headerName] == Api::RECIPIENT_COUNT_LIMIT) {
                if (false === $this->autoSend) {
                    throw new TooManyParameters(ExceptionMessages::TOO_MANY_RECIPIENTS);
                }
                $this->sendMessage();
            }
        }

        $compiledAddress = $this->parseAddress($address, $variables);

        if (isset($this->message[$headerName])) {
            array_push($this->message[$headerName], $compiledAddress);
        } elseif ('h:reply-to' == $headerName) {
            $this->message[$headerName] = $compiledAddress;
        } else {
            $this->message[$headerName] = [$compiledAddress];
        }

        if (array_key_exists($headerName, $this->counters['recipients'])) {
            $this->counters['recipients'][$headerName] += 1;
            if (is_array($variables) && !array_key_exists('id', $variables)) {
                $variables['id'] = $this->counters['recipients'][$headerName];
            }
        }
        $this->batchRecipientAttributes["$address"] = $variables;
    }

    /**
     * @param array $message
     * @param array $files
     *
     * @throws MissingRequiredMIMEParameters
     */
    public function sendMessage($message = [], $files = [])
    {
        if (count($message) < 1) {
            $message = $this->message;
            $files = $this->files;
        }
        if (!array_key_exists('from', $message)) {
            throw new MissingRequiredMIMEParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        } elseif (!array_key_exists('to', $message)) {
            throw new MissingRequiredMIMEParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        } elseif (!array_key_exists('subject', $message)) {
            throw new MissingRequiredMIMEParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        } elseif ((!array_key_exists('text', $message) && !array_key_exists('html', $message))) {
            throw new MissingRequiredMIMEParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        } else {
            $message['recipient-variables'] = json_encode($this->batchRecipientAttributes);
            $response = $this->restClient->post($this->endpointUrl, $message, $files);
            $this->batchRecipientAttributes = [];
            $this->counters['recipients']['to'] = 0;
            $this->counters['recipients']['cc'] = 0;
            $this->counters['recipients']['bcc'] = 0;
            unset($this->message['to']);
            array_push($this->messageIds, $response->http_response_body->id);
        }
    }

    /**
     * @throws MissingRequiredMIMEParameters
     */
    public function finalize()
    {
        $this->sendMessage();
    }

    /**
     * @return string[]
     */
    public function getMessageIds()
    {
        return $this->messageIds;
    }
}
