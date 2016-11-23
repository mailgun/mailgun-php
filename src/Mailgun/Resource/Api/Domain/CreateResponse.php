<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Assert;
use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class CreateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Domain
     */
    private $domain;

    /**
     * @var DnsRecord[]
     */
    private $inboundDnsRecords;

    /**
     * @var DnsRecord[]
     */
    private $outboundDnsRecords;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        Assert::keyExists($data, 'domain');
        Assert::keyExists($data, 'message');
        Assert::keyExists($data, 'receiving_dns_records');
        Assert::keyExists($data, 'sending_dns_records');

        $domain = Domain::create($data['domain']);
        $rx = [];
        $tx = [];

        foreach ($data['receiving_dns_records'] as $item) {
            $rx[] = DnsRecord::create($item);
        }
        foreach ($data['sending_dns_records'] as $item) {
            $tx[] = DnsRecord::create($item);
        }

        return new self($domain, $rx, $tx, $data['message']);
    }

    /**
     * @param Domain      $domainInfo
     * @param DnsRecord[] $rxRecords
     * @param DnsRecord[] $txRecords
     * @param string      $message
     */
    private function __construct(Domain $domainInfo, array $rxRecords, array $txRecords, $message)
    {
        $this->domain = $domainInfo;
        $this->inboundDnsRecords = $rxRecords;
        $this->outboundDnsRecords = $txRecords;
    }

    /**
     * @return Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return DnsRecord[]
     */
    public function getInboundDNSRecords()
    {
        return $this->inboundDnsRecords;
    }

    /**
     * @return DnsRecord[]
     */
    public function getOutboundDNSRecords()
    {
        return $this->outboundDnsRecords;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
