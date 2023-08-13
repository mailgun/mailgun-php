<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Message;

use Mailgun\Model\ApiResponse;
use Psr\Http\Message\StreamInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ShowResponse implements ApiResponse
{
    private $recipient;
    private $bodyMime;
    private $recipients;
    private $sender;
    private $from;
    private $subject;
    private $bodyPlain;
    private $strippedText;
    private $strippedSignature;
    private $bodyHtml;
    private $strippedHtml;
    private $attachments;
    private $messageUrl;
    private $contentIdMap;
    private $messageHeaders;
    /**
     * @var StreamInterface|null
     */
    private $rawStream;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->recipients = $data['recipients'] ?? null;
        $model->sender = $data['sender'] ?? null;
        $model->from = $data['from'] ?? null;
        $model->subject = $data['subject'] ?? null;
        $model->bodyPlain = $data['body-plain'] ?? null;
        $model->strippedText = $data['stripped-text'] ?? null;
        $model->strippedSignature = $data['stripped-signature'] ?? null;
        $model->bodyHtml = $data['body-html'] ?? null;
        $model->strippedHtml = $data['stripped-html'] ?? null;
        $model->messageUrl = $data['message-url'] ?? null;
        $model->messageHeaders = $data['message-headers'] ?? [];
        $model->recipient = $data['recipient'] ?? null;
        $model->bodyMime = $data['body-mime'] ?? null;
        $model->attachments = $data['attachments'] ?? [];
        $model->contentIdMap = $data['content-id-map'] ?? null;
        $model->rawStream = $data['raw_stream'] ?? null;

        return $model;
    }

    /**
     * Only available with message/rfc2822.
     */
    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    /**
     * Only available with message/rfc2822.
     */
    public function getBodyMime(): ?string
    {
        return $this->bodyMime;
    }

    public function getRecipients(): ?string
    {
        return $this->recipients;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getBodyPlain(): ?string
    {
        return $this->bodyPlain;
    }

    public function getStrippedText(): ?string
    {
        return $this->strippedText;
    }

    public function getStrippedSignature(): ?string
    {
        return $this->strippedSignature;
    }

    public function getBodyHtml(): ?string
    {
        return $this->bodyHtml;
    }

    public function getStrippedHtml(): ?string
    {
        return $this->strippedHtml;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getMessageUrl(): ?string
    {
        return $this->messageUrl;
    }

    public function getContentIdMap(): ?array
    {
        return $this->contentIdMap;
    }

    public function getMessageHeaders(): array
    {
        return $this->messageHeaders;
    }

    /**
     * Only available with message/rfc2822.
     *
     * @return StreamInterface|null
     */
    public function getRawStream(): ?StreamInterface
    {
        return $this->rawStream;
    }

    /**
     * @param StreamInterface|null $rawStream
     */
    public function setRawStream(?StreamInterface $rawStream): void
    {
        $this->rawStream = $rawStream;
    }
}
