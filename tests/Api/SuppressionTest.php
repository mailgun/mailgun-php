<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Suppression;

/**
 *
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class SuppressionTest extends TestCase
{
    public function testBounces()
    {
        $api = $this->getApiInstance();
        $this->assertInstanceOf(Suppression\Bounce::class, $api->bounces());
    }

    public function testComplaints()
    {
        $api = $this->getApiInstance();
        $this->assertInstanceOf(Suppression\Complaint::class, $api->complaints());
    }

    public function testUnsubscribes()
    {
        $api = $this->getApiInstance();
        $this->assertInstanceOf(Suppression\Unsubscribe::class, $api->unsubscribes());
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Suppression::class;
    }
}
