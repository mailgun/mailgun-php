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

final class DomainKeyResponse implements ApiResponse
{
    private array $items = [];
    private string $signingDomain;
    private string $selector;
    private DnsRecord $dnsRecord;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        if (isset($data['items'])) {
            $object = new self();
            $items = [];
            foreach ($data['items'] as $item) {
                $model = new self();
                $model->setSelector($item['selector'] ?? '');
                $model->setSigningDomain($item['signing_domain'] ?? '');
                if (!empty($item['dns_record'])) {
                    $model->setDnsRecord(DnsRecord::create($item['dns_record']));
                }

                $items[] = $model;
            }
            $object->setItems($items);

            return $object;
        }

        $model = new self();
        $model->setSelector($data['selector'] ?? '');
        $model->setDnsRecord(DnsRecord::create($data));
        $model->setSigningDomain($data['signing_domain'] ?? '');

        return $model;
    }

    /**
     * @return string
     */
    public function getSigningDomain(): string
    {
        return $this->signingDomain;
    }

    /**
     * @param string $signingDomain
     */
    public function setSigningDomain(string $signingDomain): void
    {
        $this->signingDomain = $signingDomain;
    }

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    /**
     * @return DnsRecord
     */
    public function getDnsRecord(): DnsRecord
    {
        return $this->dnsRecord;
    }

    /**
     * @param DnsRecord $dnsRecord
     */
    public function setDnsRecord(DnsRecord $dnsRecord): void
    {
        $this->dnsRecord = $dnsRecord;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    private function __construct()
    {
    }
}
