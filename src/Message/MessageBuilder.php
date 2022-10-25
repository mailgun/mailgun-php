<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Message;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
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
    public const RECIPIENT_COUNT_LIMIT = 1000;

    public const CAMPAIGN_ID_LIMIT = 3;

    public const TAG_LIMIT = 3;

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
     * @param  array  $params
     * @param  string $key
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
     * @param  array  $params {
     *                        full_name?: string,
     *                        first?: string,
     *                        last?: string,
     *                        }
     * @return string
     */
    private function getFullName(array $params): string
    {
        if (isset($params['full_name'])) {
            return $this->get($params, 'full_name', '');
        }

        return trim(sprintf('%s %s', $this->get($params, 'first', ''), $this->get($params, 'last', '')));
    }

    /**
     * @param  string $address
     * @param  array  $variables {
     *                           full_name?: string,
     *                           first?: string,
     *                           last?: string,
     *                           }
     * @return string
     */
    protected function parseAddress(string $address, array $variables): string
    {
        $fullName = $this->getFullName($variables);
        if (!empty($fullName)) {
            return sprintf('"%s" <%s>', $fullName, $address);
        }

        return $address;
    }

    /**
     * @param  string         $headerName
     * @param  string         $address
     * @param  array          $variables  {
     *                                    full_name?: string,
     *                                    first?: string,
     *                                    last?: string,
     *                                    }
     * @return MessageBuilder
     */
    protected function addRecipient(string $headerName, string $address, array $variables): self
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
            ++$this->counters['recipients'][$headerName];
        }

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *                          id?: string,
     *                          full_name?: string,
     *                          first?: string,
     *                          last?: string,
     *                          }
     *
     * @return MessageBuilder
     * @throws TooManyRecipients
     */
    public function addToRecipient(string $address, array $variables = []): self
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
     *                          id?: string,
     *                          full_name?: string,
     *                          first?: string,
     *                          last?: string,
     *                          }
     *
     * @return MessageBuilder
     * @throws TooManyRecipients
     */
    public function addCcRecipient(string $address, array $variables = []): self
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
     *                          id?: string,
     *                          full_name?: string,
     *                          first?: string,
     *                          last?: string,
     *                          }
     *
     * @return MessageBuilder
     * @throws TooManyRecipients
     */
    public function addBccRecipient(string $address, array $variables = []): self
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
     *                          id?: string,
     *                          full_name?: string,
     *                          first?: string,
     *                          last?: string,
     *                          }
     *
     * @return MessageBuilder
     */
    public function setFromAddress(string $address, array $variables = []): self
    {
        $this->addRecipient('from', $address, $variables);

        return $this;
    }

    /**
     * @param string $address
     * @param array  $variables {
     *                          id?: string,
     *                          full_name?: string,
     *                          first?: string,
     *                          last?: string,
     *                          }
     *
     * @return MessageBuilder
     */
    public function setReplyToAddress(string $address, array $variables = []): self
    {
        $this->addRecipient('h:reply-to', $address, $variables);

        return $this;
    }

    /**
     * @param  string $subject
     * @return $this
     */
    public function setSubject(string $subject): self
    {
        $this->message['subject'] = $subject;

        return $this;
    }

    /**
     * @param string $template Name of the Mailgun template
     */
    public function setTemplate(string $template): self
    {
        $this->message['template'] = $template;

        return $this;
    }

    /**
     * @param  string $headerName
     * @return $this
     */
    public function addCustomHeader(string $headerName, $headerData): self
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
     * @param  string $textBody
     * @return $this
     */
    public function setTextBody(string $textBody): self
    {
        $this->message['text'] = $textBody;

        return $this;
    }

    /**
     * @param  string $htmlBody
     * @return $this
     */
    public function setHtmlBody(string $htmlBody): self
    {
        $this->message['html'] = $htmlBody;

        return $this;
    }

    /**
     * @param  string      $attachmentPath
     * @param  string|null $attachmentName
     * @return $this
     */
    public function addAttachment(string $attachmentPath, string $attachmentName = null): self
    {
        if (!isset($this->message['attachment'])) {
            $this->message['attachment'] = [];
        }

        $this->message['attachment'][] = [
            'filePath' => $attachmentPath,
            'filename' => $attachmentName,
        ];

        return $this;
    }

    /**
     * @param  string      $attachmentContent
     * @param  string|null $attachmentName
     * @return $this
     */
    public function addStringAttachment(string $attachmentContent, string $attachmentName = null): self
    {
        if (!isset($this->message['attachment'])) {
            $this->message['attachment'] = [];
        }

        $this->message['attachment'][] = [
            'fileContent' => $attachmentContent,
            'filename' => $attachmentName,
        ];

        return $this;
    }

    /**
     * @param  string      $inlineImagePath
     * @param  string|null $inlineImageName
     * @return $this
     */
    public function addInlineImage(string $inlineImagePath, string $inlineImageName = null): self
    {
        if (!isset($this->message['inline'])) {
            $this->message['inline'] = [];
        }

        $this->message['inline'][] = [
            'filePath' => $inlineImagePath,
            'filename' => $inlineImageName,
        ];

        return $this;
    }

    /**
     * @param  bool  $enabled
     * @return $this
     */
    public function setTestMode(bool $enabled): self
    {
        $this->message['o:testmode'] = $enabled ? 'yes' : 'no';

        return $this;
    }

    /**
     * @throws LimitExceeded
     */
    public function addCampaignId(string $campaignId): self
    {
        if ($this->counters['attributes']['campaign_id'] >= self::CAMPAIGN_ID_LIMIT) {
            throw LimitExceeded::create('campaigns', self::CAMPAIGN_ID_LIMIT);
        }
        if (isset($this->message['o:campaign'])) {
            array_push($this->message['o:campaign'], $campaignId);
        } else {
            $this->message['o:campaign'] = [$campaignId];
        }
        ++$this->counters['attributes']['campaign_id'];

        return $this;
    }

    /**
     * @throws LimitExceeded
     */
    public function addTag(string $tag): self
    {
        if ($this->counters['attributes']['tag'] >= self::TAG_LIMIT) {
            throw LimitExceeded::create('tags', self::TAG_LIMIT);
        }

        if (isset($this->message['o:tag'])) {
            array_push($this->message['o:tag'], $tag);
        } else {
            $this->message['o:tag'] = [$tag];
        }
        ++$this->counters['attributes']['tag'];

        return $this;
    }

    /**
     * @param  bool  $enabled
     * @return $this
     */
    public function setDkim(bool $enabled): self
    {
        $this->message['o:dkim'] = $enabled ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param  bool  $enabled
     * @return $this
     */
    public function setOpenTracking(bool $enabled): self
    {
        $this->message['o:tracking-opens'] = $enabled ? 'yes' : 'no';

        return $this;
    }

    /**
     * @param  bool  $enabled
     * @param  bool  $htmlOnly
     * @return $this
     */
    public function setClickTracking(bool $enabled, bool $htmlOnly = false): self
    {
        $value = 'no';
        if ($enabled) {
            $value = 'yes';
            if ($htmlOnly) {
                $value = 'htmlonly';
            }
        }

        $this->message['o:tracking-clicks'] = $value;

        return $this;
    }

    /**
     * @param  string      $timeDate
     * @param  string|null $timeZone
     * @return $this
     * @throws \Exception
     */
    public function setDeliveryTime(string $timeDate, string $timeZone = null): self
    {
        if (null !== $timeZone) {
            $timeZoneObj = new DateTimeZone($timeZone);
        } else {
            $timeZoneObj = new DateTimeZone('UTC');
        }

        $dateTimeObj = new DateTime($timeDate, $timeZoneObj);
        $formattedTimeDate = $dateTimeObj->format(DateTimeInterface::RFC2822);
        $this->message['o:deliverytime'] = $formattedTimeDate;

        return $this;
    }

    /**
     * @param  string $customName
     * @return $this
     */
    public function addCustomData(string $customName, $data): self
    {
        $this->message['v:'.$customName] = json_encode($data);

        return $this;
    }

    /**
     * @param  string $parameterName
     * @return $this
     */
    public function addCustomParameter(string $parameterName, $data): self
    {
        if (isset($this->message[$parameterName])) {
            $this->message[$parameterName][] = $data;
        } else {
            $this->message[$parameterName] = [$data];
        }

        return $this;
    }

    /**
     * @param  array $message
     * @return $this
     */
    public function setMessage(array $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }
}
