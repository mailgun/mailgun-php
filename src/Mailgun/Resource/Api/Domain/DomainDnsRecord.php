<?php

/**
 * Copyright (C) 2013-2016 Mailgun.
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */
namespace Mailgun\Resource\Api\Domain;

use Mailgun\Assert;
use Mailgun\Resource\CreatableFromArray;

/**
 * Represents a single DNS record for a domain.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class DomainDnsRecord implements CreatableFromArray
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string|null
     */
    private $priority;

    /**
     * @var string
     */
    private $valid;

    /**
     * @param array $data
     *
     * @return DomainDnsRecord[]|array|ResponseInterface
     */
    public static function createFromArray(array $data)
    {
        $items = [];

        foreach ($data as $item) {
            Assert::keyExists($item, 'record_type');
            Assert::keyExists($item, 'value');
            Assert::keyExists($item, 'valid');

            $items[] = new static(
                array_key_exists('name', $item) ? $item['name'] : null,
                $item['record_type'],
                $item['value'],
                array_key_exists('priority', $item) ? $item['priority'] : null,
                $item['valid']
            );
        }

        return $items;
    }

    /**
     * @param string|null $name     Name of the record, as used in CNAME, etc.
     * @param string      $type     DNS record type
     * @param string      $value    DNS record value
     * @param string|null $priority Record priority, used for MX
     * @param string      $valid    DNS record has been added to domain DNS?
     */
    public function __construct($name, $type, $value, $priority, $valid)
    {
        Assert::nullOrString($name);
        Assert::string($type);
        Assert::string($value);
        Assert::nullOrString($priority);
        Assert::string($valid);

        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->priority = $priority;
        $this->valid = $valid;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return 'valid' === $this->value;
    }

    /**
     * @return string
     */
    public function getValidity()
    {
        return $this->valid;
    }
}
