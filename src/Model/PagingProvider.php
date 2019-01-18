<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
interface PagingProvider
{
    /**
     * Returns the `$paging->next` URL.
     */
    public function getNextUrl(): ?string;

    /**
     * Returns the `$paging->prev` URL.
     */
    public function getPreviousUrl(): ?string;

    /**
     * Returns the `$paging->first` URL.
     */
    public function getFirstUrl(): ?string;

    /**
     * Returns the `$paging->last` URL.
     */
    public function getLastUrl(): ?string;
}
