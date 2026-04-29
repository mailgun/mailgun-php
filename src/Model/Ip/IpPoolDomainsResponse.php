<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Ip;

use Mailgun\Model\ApiResponse;

final class IpPoolDomainsResponse implements ApiResponse
{
    /** @var array[] */
    private array $domains;

    private ?string $nextPage;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->domains = $data['domains'] ?? [];
        $model->nextPage = $data['paging']['next'] ?? null;

        return $model;
    }

    /** @return array[] */
    public function getDomains(): array
    {
        return $this->domains;
    }

    public function getNextPage(): ?string
    {
        return $this->nextPage;
    }
}
