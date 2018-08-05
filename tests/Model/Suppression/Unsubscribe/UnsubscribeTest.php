<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Unsubscribe;

use Mailgun\Model\Suppression\Unsubscribe\Unsubscribe;
use Mailgun\Tests\Model\BaseModelTest;

class UnsubscribeTest extends BaseModelTest
{
    /**
     * @test
     */
    public function it_gets_empty_list_of_tags_by_default()
    {
        $unsubscribe = Unsubscribe::create(['address' => 'dummy@mailgun.net']);
        $this->assertEquals([], $unsubscribe->getTags());
    }

    /**
     * @test
     */
    public function it_gets_tags()
    {
        $tags = ['tag1', 'tag2'];
        $unsubscribe = Unsubscribe::create(['address' => 'dummy@mailgun.net', 'tags' => $tags]);
        $this->assertEquals($tags, $unsubscribe->getTags());
    }
}
