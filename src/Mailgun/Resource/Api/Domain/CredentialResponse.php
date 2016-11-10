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
 * @author Sean Johnson <sean@mailgun.com>
 */
final class CredentialResponse implements ApiResponse
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var CredentialResponseItem[]
     */
    private $items;

    /**
     * @param array $data
     *
     * @return CredentialResponse
     */
    public static function create(array $data)
    {
        $items = [];

        Assert::keyExists($data, 'total_count');
        Assert::keyExists($data, 'items');

        foreach ($data['items'] as $item) {
            $items[] = CredentialResponseItem::create($item);
        }

        return new self($data['total_count'], $items);
    }

    /**
     * @param int                      $totalCount
     * @param CredentialResponseItem[] $items
     */
    private function __construct($totalCount, array $items)
    {
        Assert::integer($totalCount);
        Assert::isArray($items);
        Assert::allIsInstanceOf($items, 'Mailgun\Resource\Api\Domain\Credential');

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
     * @return CredentialResponseItem[]
     */
    public function getCredentials()
    {
        return $this->items;
    }
}
