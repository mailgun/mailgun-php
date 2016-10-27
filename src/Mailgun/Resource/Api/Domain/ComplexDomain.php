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
 * ComplexDomain uses DomainTrait and exposes a "complex" constructor
 * where an array or \stdClass can be passed in to find the appropriate
 * fields.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class ComplexDomain implements CreatableFromArray
{
    /**
     * @var SimpleDomain
     */
    private $domain;

    /**
     * @var DomainDnsRecord[]
     */
    private $inboundDnsRecords;

    /**
     * @var DomainDnsRecord[]
     */
    private $outboundDnsRecords;

    /**
     * @param array $data
     *
     * @return ComplexDomain
     */
    public static function createFromArray(array $data)
    {
        Assert::keyExists($data, 'domain');
        Assert::keyExists($data, 'receiving_dns_records');
        Assert::keyExists($data, 'sending_dns_records');

        // Let DomainDnsRecord::createFromArray() handle validation of
        // the `receiving_dns_records` and `sending_dns_records` data.
        // Also let SimpleDomain::createFromArray() handle validation of
        // the `domain` fields.
        return new static(
            SimpleDomain::createFromArray($data['domain']),
            DomainDnsRecord::createFromArray($data['receiving_dns_records']),
            DomainDnsRecord::createFromArray($data['sending_dns_records'])
        );
    }

    /**
     * @param SimpleDomain $domainInfo
     * @param array        $rxRecords  Array of DomainDnsRecord instances
     * @param array        $txRecords  Array of DomainDnsRecord instances
     */
    public function __construct(SimpleDomain $domainInfo, array $rxRecords, array $txRecords)
    {
        $this->domain = $domainInfo;
        $this->inboundDnsRecords = $rxRecords;
        $this->outboundDnsRecords = $txRecords;
    }

    /**
     * @return SimpleDomain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return DomainDnsRecord[]
     */
    public function getInboundDNSRecords()
    {
        return $this->inboundDnsRecords;
    }

    /**
     * @return DomainDnsRecord[]
     */
    public function getOutboundDNSRecords()
    {
        return $this->outboundDnsRecords;
    }
}
