<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait PaginationResponse
{
    /**
     * @var array
     */
    protected $paging;

    /**
     * @return string
     */
    public function getNextUrl()
    {
        if (!isset($this->paging['next'])) {
            return;
        }

        return $this->paging['next'];
    }

    /**
     * @return string
     */
    public function getPreviousUrl()
    {
        if (!isset($this->paging['previous'])) {
            return;
        }

        return $this->paging['previous'];
    }

    /**
     * @return string
     */
    public function getFirstUrl()
    {
        if (!isset($this->paging['first'])) {
            return;
        }

        return $this->paging['first'];
    }

    /**
     * @return string
     */
    public function getLastUrl()
    {
        if (!isset($this->paging['last'])) {
            return;
        }

        return $this->paging['last'];
    }
}
