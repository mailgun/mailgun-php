<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Templates;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;
use Psr\Http\Message\StreamInterface;

final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    private $items;

    /**
     * @var StreamInterface|null
     */
    private $rawStream;

    private function __construct()
    {
    }

    /**
     * @param  array  $data
     * @return static
     */
    public static function create(array $data): self
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = Template::create($item);
        }

        $model = new self();
        $model->items = $items;

        // Fix http urls that is coming from server
        $data['paging'] = array_map(
            static function (string $url) {
                return str_replace('http://', 'https://', $url);
            }, $data['paging']
        );

        $model->paging = $data['paging'];

        return $model;
    }

    /**
     * @return Template[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Only available with message/rfc2822.
     *
     * @return StreamInterface|null
     */
    public function getRawStream(): ?StreamInterface
    {
        return $this->rawStream;
    }

    /**
     * @param StreamInterface|null $rawStream
     */
    public function setRawStream(?StreamInterface $rawStream): void
    {
        $this->rawStream = $rawStream;
    }
}
