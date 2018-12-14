<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\Tag as TagModel;
use Mailgun\Tests\Model\BaseModelTest;

class TagTest extends BaseModelTest
{
    public function testCreate()
    {
        $expectedTag = 'foo';
        $expectedDescription = 'bar';
        $tag = TagModel::create([
            'tag' => $expectedTag,
            'description' => $expectedDescription,
            'first-seen' => '2018-12-13T05:00:00Z',
            'last-seen' => '2018-12-13T12:00:00Z'
        ]);

        $this->assertInstanceOf(TagModel::class, $tag);
        $this->assertSame($expectedTag, $tag->getTag());
        $this->assertSame($expectedDescription, $tag->getDescription());
    }
}
