<?php

declare(strict_types=1);

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
    private $message;
    private $domain;
    private $inboundDnsRecords;
    private $outboundDnsRecords;

    public static function create(array $data): self
    {
        $rx = [];
        $tx = [];
        $domain = null;

        if (isset($data['domain'])) {
            $domain = Domain::create($data['domain']);
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

        $model = new static();
        $model->domain = $domain;
        $model->inboundDnsRecords = $rx;
        $model->outboundDnsRecords = $tx;
        $model->message = $data['message'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * @return DnsRecord[] tx
     */
    public function getInboundDNSRecords(): array
    {
        return $this->inboundDnsRecords;
    }

    /**
     * @return DnsRecord[] tx
     */
    public function getOutboundDNSRecords(): array
    {
        return $this->outboundDnsRecords;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
