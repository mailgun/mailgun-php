<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\Tag as TagModel;
use Mailgun\Tests\Model\BaseModel;

class Tag extends BaseModel
{
    public function testCreate()
    {
        $expectedTag = 'foo';
        $expectedDescription = 'bar';
        $expectedFirstSeen = '2018-12-13T05:00:00Z';
        $expectedLastSeeen = '2018-12-13T12:00:00Z';
        $tag = TagModel::create(
            [
            'tag' => $expectedTag,
            'description' => $expectedDescription,
            'first-seen' => $expectedFirstSeen,
            'last-seen' => $expectedLastSeeen,
            ]
        );

        $this->assertInstanceOf(TagModel::class, $tag);
        $this->assertSame($expectedTag, $tag->getTag());
        $this->assertSame($expectedDescription, $tag->getDescription());
        $this->assertEquals($expectedFirstSeen, $tag->getFirstSeen()->format('Y-m-d\TH:i:s\Z'));
        $this->assertEquals($expectedLastSeeen, $tag->getLastSeen()->format('Y-m-d\TH:i:s\Z'));
    }
}
