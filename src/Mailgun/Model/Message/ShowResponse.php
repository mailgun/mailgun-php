<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Message;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ShowResponse implements ApiResponse
{
    /**
     * Only available with message/rfc2822.
     *
     * @var string
     */
    private $recipient;

    /**
     * Only available with message/rfc2822.
     *
     * @var string
     */
    private $bodyMime;

    /**
     * @var string
     */
    private $recipients;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $bodyPlain;

    /**
     * @var string
     */
    private $strippedText;

    /**
     * @var string
     */
    private $strippedSignature;

    /**
     * @var string
     */
    private $bodyHtml;

    /**
     * @var string
     */
    private $strippedHtml;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var string
     */
    private $messageUrl;

    /**
     * @var string
     */
    private $contentIdMap;

    /**
     * @var array
     */
    private $messageHeaders;

    /**
     * Do not let this object be creted without the ::create.
     */
    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return ShowResponse
     */
    public static function create(array $data)
    {
        $response = new self();

        if (isset($data['recipients'])) {
            $response->setRecipients($data['recipients']);
        }
        if (isset($data['sender'])) {
            $response->setSender($data['sender']);
        }
        if (isset($data['from'])) {
            $response->setFrom($data['from']);
        }
        if (isset($data['subject'])) {
            $response->setSubject($data['subject']);
        }
        if (isset($data['body-plain'])) {
            $response->setBodyPlain($data['body-plain']);
        }
        if (isset($data['stripped-text'])) {
            $response->setStrippedText($data['stripped-text']);
        }
        if (isset($data['stripped-signature'])) {
            $response->setStrippedSignature($data['stripped-signature']);
        }
        if (isset($data['body-html'])) {
            $response->setBodyHtml($data['body-html']);
        }
        if (isset($data['stripped-html'])) {
            $response->setStrippedHtml($data['stripped-html']);
        }
        if (isset($data['message-url'])) {
            $response->setMessageUrl($data['message-url']);
        }
        if (isset($data['message-headers'])) {
            $response->setMessageHeaders($data['message-headers']);
        }
        if (isset($data['recipient'])) {
            $response->setRecipient($data['recipient']);
        }
        if (isset($data['body-mime'])) {
            $response->setBodyMime($data['body-mime']);
        }
        if (isset($data['attachments'])) {
            $response->setAttachments($data['attachments']);
        }
        if (isset($data['content-id-map'])) {
            $response->setContentIdMap($data['content-id-map']);
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    private function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     */
    public function getBodyMime()
    {
        return $this->bodyMime;
    }

    /**
     * @param string $bodyMime
     */
    private function setBodyMime($bodyMime)
    {
        $this->bodyMime = $bodyMime;
    }

    /**
     * @return string
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param string $recipients
     */
    private function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    private function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    private function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    private function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBodyPlain()
    {
        return $this->bodyPlain;
    }

    /**
     * @param string $bodyPlain
     */
    private function setBodyPlain($bodyPlain)
    {
        $this->bodyPlain = $bodyPlain;
    }

    /**
     * @return string
     */
    public function getStrippedText()
    {
        return $this->strippedText;
    }

    /**
     * @param string $strippedText
     */
    private function setStrippedText($strippedText)
    {
        $this->strippedText = $strippedText;
    }

    /**
     * @return string
     */
    public function getStrippedSignature()
    {
        return $this->strippedSignature;
    }

    /**
     * @param string $strippedSignature
     */
    private function setStrippedSignature($strippedSignature)
    {
        $this->strippedSignature = $strippedSignature;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     */
    private function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * @return string
     */
    public function getStrippedHtml()
    {
        return $this->strippedHtml;
    }

    /**
     * @param string $strippedHtml
     */
    private function setStrippedHtml($strippedHtml)
    {
        $this->strippedHtml = $strippedHtml;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    private function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getMessageUrl()
    {
        return $this->messageUrl;
    }

    /**
     * @param string $messageUrl
     */
    private function setMessageUrl($messageUrl)
    {
        $this->messageUrl = $messageUrl;
    }

    /**
     * @return string
     */
    public function getContentIdMap()
    {
        return $this->contentIdMap;
    }

    /**
     * @param string $contentIdMap
     */
    public function setContentIdMap($contentIdMap)
    {
        $this->contentIdMap = $contentIdMap;
    }

    /**
     * @return array
     */
    public function getMessageHeaders()
    {
        return $this->messageHeaders;
    }

    /**
     * @param array $messageHeaders
     */
    private function setMessageHeaders(array $messageHeaders)
    {
        $this->messageHeaders = $messageHeaders;
    }
}
