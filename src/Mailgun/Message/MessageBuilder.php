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
    private function get($params, $key, $default)
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
    protected function parseAddress($address, array $variables)
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
     */
    protected function addRecipient($headerName, $address, array $variables)
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
     */
    public function addToRecipient($address, array $variables = [])
    {
        if ($this->counters['recipients']['to'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('to');
        }
        $this->addRecipient('to', $address, $variables);
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
     */
    public function addCcRecipient($address, array $variables = [])
    {
        if ($this->counters['recipients']['cc'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('cc');
        }

        $this->addRecipient('cc', $address, $variables);
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
     */
    public function addBccRecipient($address, array $variables)
    {
        if ($this->counters['recipients']['bcc'] > self::RECIPIENT_COUNT_LIMIT) {
            throw TooManyRecipients::create('bcc');
        }

        $this->addRecipient('bcc', $address, $variables);
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
     */
    public function setFromAddress($address, array $variables = [])
    {
        $this->addRecipient('from', $address, $variables);
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
     */
    public function setReplyToAddress($address, array $variables = [])
    {
        $this->addRecipient('h:reply-to', $address, $variables);
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->message['subject'] = $subject;
    }

    /**
     * @param string $headerName
     * @param mixed  $headerData
     */
    public function addCustomHeader($headerName, $headerData)
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
    }

    /**
     * @param string $textBody
     */
    public function setTextBody($textBody)
    {
        $this->message['text'] = $textBody;
    }

    /**
     * @param string $htmlBody
     *
     * @return string
     */
    public function setHtmlBody($htmlBody)
    {
        $this->message['html'] = $htmlBody;
    }

    /**
     * @param string      $attachmentPath
     * @param string|null $attachmentName
     */
    public function addAttachment($attachmentPath, $attachmentName = null)
    {
        if (!isset($this->message['attachment'])) {
            $this->message['attachment'] = [];
        }

        $this->message['attachment'][] = [
            'filePath' => $attachmentPath,
            'remoteName' => $attachmentName,
        ];
    }

    /**
     * @param string      $inlineImagePath
     * @param string|null $inlineImageName
     */
    public function addInlineImage($inlineImagePath, $inlineImageName = null)
    {
        if (!isset($this->message['inline'])) {
            $this->message['inline'] = [];
        }

        $this->message['inline'][] = [
            'filePath' => $inlineImagePath,
            'remoteName' => $inlineImageName,
        ];
    }

    /**
     * @param bool $enabled
     */
    public function setTestMode($enabled)
    {
        $this->message['o:testmode'] = $this->boolToString($enabled);
    }

    /**
     * @param string $campaignId
     *
     * @throws LimitExceeded
     */
    public function addCampaignId($campaignId)
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
    }

    /**
     * @param string $tag
     *
     * @throws LimitExceeded
     */
    public function addTag($tag)
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
    }

    /**
     * @param bool $enabled
     */
    public function setDkim($enabled)
    {
        $this->message['o:dkim'] = $this->boolToString($enabled);
    }

    /**
     * @param bool $enabled
     *
     * @return string
     */
    public function setOpenTracking($enabled)
    {
        $this->message['o:tracking-opens'] = $this->boolToString($enabled);
    }

    /**
     * @param bool $enabled
     *
     * @return string
     */
    public function setClickTracking($enabled)
    {
        $this->message['o:tracking-clicks'] = $this->boolToString($enabled);
    }

    /**
     * @param string      $timeDate
     * @param string|null $timeZone
     *
     * @return string
     */
    public function setDeliveryTime($timeDate, $timeZone = null)
    {
        if (null !== $timeZone) {
            $timeZoneObj = new \DateTimeZone($timeZone);
        } else {
            $timeZoneObj = new \DateTimeZone('UTC');
        }

        $dateTimeObj = new \DateTime($timeDate, $timeZoneObj);
        $formattedTimeDate = $dateTimeObj->format(\DateTime::RFC2822);
        $this->message['o:deliverytime'] = $formattedTimeDate;

        return $this->message['o:deliverytime'];
    }

    /**
     * @param string $customName
     * @param mixed  $data
     */
    public function addCustomData($customName, $data)
    {
        $this->message['v:'.$customName] = json_encode($data);
    }

    /**
     * @param string $parameterName
     * @param mixed  $data
     *
     * @return mixed
     */
    public function addCustomParameter($parameterName, $data)
    {
        if (isset($this->message[$parameterName])) {
            $this->message[$parameterName][] = $data;
        } else {
            $this->message[$parameterName] = [$data];
        }

        return $this->message[$parameterName];
    }

    /**
     * @param array $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $enabled
     *
     * @return string
     */
    private function boolToString($enabled)
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
