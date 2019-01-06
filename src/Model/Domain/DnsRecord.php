<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

/**
 * Represents a single DNS record for a domain.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
final class DnsRecord
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
     * @var array
     */
    private $cached;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $name = isset($data['name']) ? $data['name'] : null;
        $priority = isset($data['priority']) ? $data['priority'] : null;
        $recordType = isset($data['record_type']) ? $data['record_type'] : null;
        $value = isset($data['value']) ? $data['value'] : null;
        $valid = isset($data['valid']) ? $data['valid'] : null;
        $cached = isset($data['cached']) ? $data['cached'] : null;

        return new self($name, $recordType, $value, $priority, $valid, $cached);
    }

    /**
     * @param string|null $name     Name of the record, as used in CNAME, etc.
     * @param string      $type     DNS record type
     * @param string      $value    DNS record value
     * @param string|null $priority Record priority, used for MX
     * @param string      $valid    DNS record has been added to domain DNS?
     * @param array       $cached   DNS record current value
     */
    private function __construct($name, $type, $value, $priority, $valid, $cached)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->priority = $priority;
        $this->valid = $valid;
        $this->cached = $cached;
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
     * @return string
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
        return 'valid' === $this->valid;
    }

    /**
     * @return string
     */
    public function getValidity()
    {
        return $this->valid;
    }

    /**
     * @return array
     */
    public function getCached()
    {
        return $this->cached;
    }
}
