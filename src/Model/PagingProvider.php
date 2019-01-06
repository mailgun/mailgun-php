<?php

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
     *
     * @return string
     */
    public function getNextUrl();

    /**
     * Returns the `$paging->prev` URL.
     *
     * @return string
     */
    public function getPreviousUrl();

    /**
     * Returns the `$paging->first` URL.
     *
     * @return string
     */
    public function getFirstUrl();

    /**
     * Returns the `$paging->last` URL.
     *
     * @return string
     */
    public function getLastUrl();
}
