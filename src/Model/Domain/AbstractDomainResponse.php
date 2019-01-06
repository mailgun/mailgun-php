<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class AbstractDomainResponse implements ApiResponse
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
        $rx = [];
        $tx = [];
        $domain = null;
        $message = null;

        if (isset($data['domain'])) {
            $domain = Domain::create($data['domain']);
        }

        if (isset($data['message'])) {
            $message = $data['message'];
        }

        if (isset($data['receiving_dns_records'])) {
            foreach ($data['receiving_dns_records'] as $item) {
                $rx[] = DnsRecord::create($item);
            }
        }

        if (isset($data['sending_dns_records'])) {
            foreach ($data['sending_dns_records'] as $item) {
                $tx[] = DnsRecord::create($item);
            }
        }

        return new static($domain, $rx, $tx, $message);
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
        $this->message = $message;
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
