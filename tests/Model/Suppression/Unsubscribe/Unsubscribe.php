<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Unsubscribe;

use Mailgun\Model\Suppression\Unsubscribe\Unsubscribe as UnsubscribeAlias;
use Mailgun\Tests\Model\BaseModel;

class Unsubscribe extends BaseModel
{
    /**
     * @test
     */
    public function itGetsEmptyListOfTagsByDefault()
    {
        $unsubscribe = UnsubscribeAlias::create(['address' => 'dummy@mailgun.net']);
        $this->assertEquals([], $unsubscribe->getTags());
    }

    /**
     * @test
     */
    public function itGetsTags()
    {
        $tags = ['tag1', 'tag2'];
        $unsubscribe = UnsubscribeAlias::create(['address' => 'dummy@mailgun.net', 'tags' => $tags]);
        $this->assertEquals($tags, $unsubscribe->getTags());
    }
}
