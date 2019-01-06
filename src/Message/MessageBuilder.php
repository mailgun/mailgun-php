<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Message;

use Mailgun\Message\Exceptions\LimitExceeded;
use Mailgun\Message\Exceptions\TooManyRecipients;

/**
 * This class is used for composing a properly formed
 * message object. Dealing with arrays can be cumbersome,
 * this class makes the process easier. See the official
 * documentation (link below) for usage instructions.
 *
 * @see https://github.com/mailgun/mailgun-php/blob/master/src/Mailgun/Message/README.md
 */
class MessageBuilder
{
    const RECIPIENT_COUNT_LIMIT = 1000;

    const CAMPAIGN_ID_LIMIT = 3;

    const TAG_LIMIT = 3;

    /**
     * @var array
     */
    protected $message = [];

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $counters = [
        'recipients' => [
            'to' => 0,
            'cc' => 0,
            'bcc' => 0,
        ],
        'attributes' => [
            'attachment' => 0,
            'campaign_id' => 0,
            'custom_option' => 0,
            'tag' => 0,
        ],
    ];

    /**
     * @param array  $params
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function get(array $params, string $key, $default)
    {
        if (array_key_exists($key, $params)) {
            return $params[$key];
        }

        return $default;
    }

    /**
     * @param array $params {
     *
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @return string
     */
    private function getFullName(array $params)
    {
        if (isset($params['full_name'])) {
            return $this->get($params, 'full_name', '');
        }

        return trim(sprintf('%s %s', $this->get($params, 'first', ''), $this->get($params, 'last', '')));
    }

    /**
     * @param string $address
     * @param array  $params  {
     *
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @return string
     */
    protected function parseAddress(string $address, array $variables)
    {
        $fullName = $this->getFullName($variables);
        if (!empty($fullName)) {
            return sprintf('"%s" <%s>', $fullName, $address);
        }

        return $address;
    }

    /**
     * @param string $headerName
     * @param string $address
     * @param array  $variables  {
     *
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @return MessageBuilder
     */
    protected function addRecipient(string $headerName, string $address, array $variables)
    {
        $compiledAddress = $this->parseAddress($address, $variables);

        if ('h:reply-to' === $headerName) {
            $this->message[$headerName] = $compiledAddress;
        } elseif (isset($this->message[$headerName])) {
            $this->message[$headerName][] = $compiledAddress;
        } else {
            $this->message[$headerName] = [$compiledAddress];
        }
        if (array_key_exists($headerName, $this->counters['recipients'])) {
            $this->counters['recipients'][$headerName] += 1;
        }

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *
     *     @var string $id If used with BatchMessage
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @throws TooManyRecipients
     *
     * @return MessageBuilder
     */
    public function addToRecipient(string $address, array $variables = [])
    {
        if ($this->counters['recipients']['to'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('to');
        }
        $this->addRecipient('to', $address, $variables);

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *
     *     @var string $id If used with BatchMessage
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @throws TooManyRecipients
     *
     * @return MessageBuilder
     */
    public function addCcRecipient(string $address, array $variables = [])
    {
        if ($this->counters['recipients']['cc'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('cc');
        }

        $this->addRecipient('cc', $address, $variables);

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *
     *     @var string $id If used with BatchMessage
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @throws TooManyRecipients
     *
     * @return MessageBuilder
     */
    public function addBccRecipient(string $address, array $variables = [])
    {
        if ($this->counters['recipients']['bcc'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('bcc');
        }

        $this->addRecipient('bcc', $address, $variables);

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *
     *     @var string $id If used with BatchMessage
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @return MessageBuilder
     */
    public function setFromAddress(string $address, array $variables = [])
    {
        $this->addRecipient('from', $address, $variables);

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *
     *     @var string $id If used with BatchMessage
     *     @var string $full_name
     *     @var string $first
     *     @var string $last
     * }
     *
     * @return MessageBuilder
     */
    public function setReplyToAddress(string $address, array $variables = [])
    {
        $this->addRecipient('h:reply-to', $address, $variables);

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return MessageBuilder
     */
    public function setSubject(string $subject)
    {
        $this->message['subject'] = $subject;

        return $this;
    }

    /**
     * @param string $headerName
     * @param mixed  $headerData
     *
     * @return MessageBuilder
     */
    public function addCustomHeader(string $headerName, $headerData)
    {
        if (!preg_match('/^h:/i', $headerName)) {
            $headerName = 'h:'.$headerName;
        }

        if (!array_key_exists($headerName, $this->message)) {
            $this->message[$headerName] = $headerData;
        } else {
            if (is_array($this->message[$headerName])) {
                $this->message[$headerName][] = $headerData;
            } else {
                $this->message[$headerName] = [$this->message[$headerName], $headerData];
            }
        }

        return $this;
    }

    /**
     * @param string $textBody
     *
     * @return MessageBuilder
     */
    public function setTextBody(string $textBody)
    {
        $this->message['text'] = $textBody;

        return $this;
    }

    /**
     * @param string $htmlBody
     *
     * @return MessageBuilder
     */
    public function setHtmlBody(string $htmlBody)
    {
        $this->message['html'] = $htmlBody;

        return $this;
    }

    /**
     * @param string      $attachmentPath
     * @param string|null $attachmentName
     *
     * @return MessageBuilder
     */
    public function addAttachment(string $attachmentPath, string $attachmentName = null)
    {
        if (!isset($this->message['attachment'])) {
            $this->message['attachment'] = [];
        }

        $this->message['attachment'][] = [
            'filePath' => $attachmentPath,
            'remoteName' => $attachmentName,
        ];

        return $this;
    }

    /**
     * @param string      $inlineImagePath
     * @param string|null $inlineImageName
     *
     * @return MessageBuilder
     */
    public function addInlineImage(string $inlineImagePath, string $inlineImageName = null)
    {
        if (!isset($this->message['inline'])) {
            $this->message['inline'] = [];
        }

        $this->message['inline'][] = [
            'filePath' => $inlineImagePath,
            'remoteName' => $inlineImageName,
        ];

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return MessageBuilder
     */
    public function setTestMode(bool $enabled)
    {
        $this->message['o:testmode'] = $this->boolToString($enabled);

        return $this;
    }

    /**
     * @param string $campaignId
     *
     * @throws LimitExceeded
     *
     * @return MessageBuilder
     */
    public function addCampaignId(string $campaignId)
    {
        if ($this->counters['attributes']['campaign_id'] >= self::CAMPAIGN_ID_LIMIT) {
            throw LimitExceeded::create('campaigns', self::CAMPAIGN_ID_LIMIT);
        }
        if (isset($this->message['o:campaign'])) {
            array_push($this->message['o:campaign'], (string) $campaignId);
        } else {
            $this->message['o:campaign'] = [(string) $campaignId];
        }
        $this->counters['attributes']['campaign_id'] += 1;

        return $this;
    }

    /**
     * @param string $tag
     *
     * @throws LimitExceeded
     *
     * @return MessageBuilder
     */
    public function addTag(string $tag)
    {
        if ($this->counters['attributes']['tag'] >= self::TAG_LIMIT) {
            throw LimitExceeded::create('tags', self::TAG_LIMIT);
        }

        if (isset($this->message['o:tag'])) {
            array_push($this->message['o:tag'], $tag);
        } else {
            $this->message['o:tag'] = [$tag];
        }
        $this->counters['attributes']['tag'] += 1;

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return MessageBuilder
     */
    public function setDkim(bool $enabled)
    {
        $this->message['o:dkim'] = $this->boolToString($enabled);

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return MessageBuilder
     */
    public function setOpenTracking(bool $enabled)
    {
        $this->message['o:tracking-opens'] = $this->boolToString($enabled);

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return MessageBuilder
     */
    public function setClickTracking(bool $enabled)
    {
        $this->message['o:tracking-clicks'] = $this->boolToString($enabled);

        return $this;
    }

    /**
     * @param string      $timeDate
     * @param string|null $timeZone
     *
     * @return MessageBuilder
     */
    public function setDeliveryTime(string $timeDate, string $timeZone = null)
    {
        if (null !== $timeZone) {
            $timeZoneObj = new \DateTimeZone($timeZone);
        } else {
            $timeZoneObj = new \DateTimeZone('UTC');
        }

        $dateTimeObj = new \DateTime($timeDate, $timeZoneObj);
        $formattedTimeDate = $dateTimeObj->format(\DateTime::RFC2822);
        $this->message['o:deliverytime'] = $formattedTimeDate;

        return $this;
    }

    /**
     * @param string $customName
     * @param mixed  $data
     *
     * @return MessageBuilder
     */
    public function addCustomData(string $customName, $data)
    {
        $this->message['v:'.$customName] = json_encode($data);

        return $this;
    }

    /**
     * @param string $parameterName
     * @param mixed  $data
     *
     * @return MessageBuilder
     */
    public function addCustomParameter(string $parameterName, $data)
    {
        if (isset($this->message[$parameterName])) {
            $this->message[$parameterName][] = $data;
        } else {
            $this->message[$parameterName] = [$data];
        }

        return $this;
    }

    public function setMessage(array $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    private function boolToString(bool $enabled): string
    {
        if (filter_var($enabled, FILTER_VALIDATE_BOOLEAN)) {
            $enabled = 'yes';
        } elseif ('html' === $enabled) {
            $enabled = 'html';
        } else {
            $enabled = 'no';
        }

        return $enabled;
    }
}
