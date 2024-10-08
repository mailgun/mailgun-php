<?php

declare(strict_types=1);

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
use Psr\Http\Client\ClientExceptionInterface;

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
    private array $batchRecipientAttributes = [];

    /**
     * @var bool
     */
    private bool $autoSend;

    /**
     * @var array
     */
    private array $messageIds = [];

    /**
     * @var string
     */
    private string $domain;

    /**
     * @var Message
     */
    private Message $api;

    /**
     * @param Message $messageApi
     * @param string  $domain
     * @param bool    $autoSend
     */
    public function __construct(Message $messageApi, string $domain, bool $autoSend)
    {
        $this->api = $messageApi;
        $this->domain = $domain;
        $this->autoSend = $autoSend;
    }

    /**
     * @param string $headerName
     * @param string $address
     * @param array  $variables  {
     *                           id?:string
     *                           full_name?: string,
     *                           first?: string,
     *                           last?: string,
     * @return MessageBuilder
     * @throws MissingRequiredParameter
     * @throws TooManyRecipients|ClientExceptionInterface
     */
    protected function addRecipient(string $headerName, string $address, array $variables): MessageBuilder
    {
        if (array_key_exists($headerName, $this->counters['recipients'])) {
            if (self::RECIPIENT_COUNT_LIMIT === $this->counters['recipients'][$headerName]) {
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
        if ($variables) {
            $this->batchRecipientAttributes[$address] = $variables;
        }

        return $this;
    }

    /**
     * @throws RuntimeException
     * @throws MissingRequiredParameter|ClientExceptionInterface
     */
    public function finalize(): void
    {
        $message = $this->message;

        if (empty($this->domain)) {
            throw new RuntimeException('You must call BatchMessage::setDomain before sending messages.');
        }

        if (empty($message['from'])) {
            throw MissingRequiredParameter::create('from');
        }

        if (empty($message['to'])) {
            throw MissingRequiredParameter::create('to');
        }

        if (empty($message['subject'])) {
            throw MissingRequiredParameter::create('subject');
        }

        if (empty($message['text']) && empty($message['html']) && empty($message['template'])) {
            throw MissingRequiredParameter::create('text", "html" or "template');
        }

        $message['recipient-variables'] = json_encode($this->batchRecipientAttributes, JSON_FORCE_OBJECT);
        $response = $this->api->send($this->domain, $message);

        $this->batchRecipientAttributes = [];
        $this->counters['recipients']['to'] = 0;
        $this->counters['recipients']['cc'] = 0;
        $this->counters['recipients']['bcc'] = 0;
        unset($this->message['to']);

        $this->messageIds[] = $response->getId();
    }

    /**
     * @return string[]
     */
    public function getMessageIds(): array
    {
        return $this->messageIds;
    }
}
