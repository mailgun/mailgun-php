<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Assert;
use Mailgun\Resource\CreatableFromArray;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class DomainListResponse implements CreatableFromArray
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var SimpleDomain[]
     */
    private $items;

    /**
     * @param array $data
     *
     * @return DomainListResponse|array|ResponseInterface
     */
    public static function createFromArray(array $data)
    {
        $items = [];

        Assert::keyExists($data, 'total_count');
        Assert::keyExists($data, 'items');

        foreach ($data['items'] as $item) {
            Assert::keyExists($item, 'name');
            Assert::keyExists($item, 'smtp_login');
            Assert::keyExists($item, 'smtp_password');
            Assert::keyExists($item, 'wildcard');
            Assert::keyExists($item, 'spam_action');
            Assert::keyExists($item, 'state');
            Assert::keyExists($item, 'created_at');

            $items[] = SimpleDomain::createFromArray($item);
            $items[] = new SimpleDomain(
                $item['name'],
                $item['smtp_login'],
                $item['smtp_password'],
                $item['wildcard'],
                $item['spam_action'],
                $item['state'],
                new \DateTime($item['created_at'])
            );
        }

        return new self($data['total_count'], $items);
    }

    /**
     * @param int            $totalCount
     * @param SimpleDomain[] $items
     */
    public function __construct($totalCount, array $items)
    {
        Assert::integer($totalCount);
        Assert::isArray($items);
        Assert::allIsInstanceOf($items, 'Mailgun\Resource\Api\Domain\SimpleDomain');

        $this->totalCount = $totalCount;
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return SimpleDomain[]
     */
    public function getDomains()
    {
        return $this->items;
    }
}
