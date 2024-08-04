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
 * @author Sean Johnson <sean@mailgun.com>
 */
final class ShowResponse implements ApiResponse
{
    private ?Domain $domain;
    private array $inboundDnsRecords;
    private array $outboundDnsRecords;

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

        $model = new self();
        $model->domain = $domain;
        $model->inboundDnsRecords = $rx;
        $model->outboundDnsRecords = $tx;

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return Domain|null
     */
    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * @return DnsRecord[]
     */
    public function getInboundDNSRecords(): array
    {
        return $this->inboundDnsRecords;
    }

    /**
     * @return DnsRecord[]
     */
    public function getOutboundDNSRecords(): array
    {
        return $this->outboundDnsRecords;
    }
}
