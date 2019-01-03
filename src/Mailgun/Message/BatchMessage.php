<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Message;

use Mailgun\Api\Message;
use Mailgun\Message\Exceptions\MissingRequiredParameter;
use Mailgun\Message\Exceptions\RuntimeException;
use Mailgun\Message\Exceptions\TooManyRecipients;

/**
 * This class is used for batch sending. See the official documentation (link below)
 * for usage instructions.
 *
 * @see https://github.com/mailgun/mailgun-php/blob/master/src/Mailgun/Message/README.md
 */
class BatchMessage extends MessageBuilder
{
    /**
     * @var array
     */
    private $batchRecipientAttributes = [];

    /**
     * @var bool
     */
    private $autoSend = true;

    /**
     * @var array
     */
    private $messageIds = [];

    /**
     * @var string
     */
    private $domain;

    /**
     * @var Message
     */
    private $api;

    /**
     * @param Message $messageApi
     * @param string  $domain
     * @param bool    $autoSend
     */
    public function __construct(Message $messageApi, $domain, $autoSend)
    {
        $this->api = $messageApi;
        $this->domain = $domain;
        $this->autoSend = $autoSend;
    }

    /**
     * @param string $headerName
     * @param string $address
     * @param array  $variables  {
     *
     *     @var string $id
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @throws MissingRequiredParameter
     * @throws TooManyRecipients
     *
     * @return BatchMessage
     */
    protected function addRecipient($headerName, $address, array $variables)
    {
        if (array_key_exists($headerName, $this->counters['recipients'])) {
            if ($this->counters['recipients'][$headerName] === self::RECIPIENT_COUNT_LIMIT) {
                if (false === $this->autoSend) {
                    throw TooManyRecipients::whenAutoSendDisabled();
                }
                $this->finalize();
            }
        }

        parent::addRecipient($headerName, $address, $variables);

        if (array_key_exists($headerName, $this->counters['recipients']) && !array_key_exists('id', $variables)) {
            $variables['id'] = $headerName.'_'.$this->counters['recipients'][$headerName];
        }

        $this->batchRecipientAttributes[(string) $address] = $variables;

        return $this;
    }

    /**
     * @throws RuntimeException
     * @throws MissingRequiredParameter
     */
    public function finalize()
    {
        $message = $this->message;

        if (empty($this->domain)) {
            throw new RuntimeException('You must call BatchMessage::setDomain before sending messages.');
        } elseif (empty($message['from'])) {
            throw MissingRequiredParameter::create('from');
        } elseif (empty($message['to'])) {
            throw MissingRequiredParameter::create('to');
        } elseif (empty($message['subject'])) {
            throw MissingRequiredParameter::create('subject');
        } elseif (empty($message['text']) && empty($message['html'])) {
            throw MissingRequiredParameter::create('text" or "html');
        } else {
            $message['recipient-variables'] = json_encode($this->batchRecipientAttributes);
            $response = $this->api->send($this->domain, $message);

            $this->batchRecipientAttributes = [];
            $this->counters['recipients']['to'] = 0;
            $this->counters['recipients']['cc'] = 0;
            $this->counters['recipients']['bcc'] = 0;
            unset($this->message['to']);

            $this->messageIds[] = $response->getId();
        }
    }

    /**
     * @return string[]
     */
    public function getMessageIds()
    {
        return $this->messageIds;
    }
}
