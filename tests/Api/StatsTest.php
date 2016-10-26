<?php

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StatsTest extends TestCase
{
    protected function getApiClass()
    {
        return 'Mailgun\Api\Stats';
    }

    public function testTotal()
    {
        $data = [
            'foo' => 'bar',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/v3/domain/stats/total', $data)
            ->willReturn(new Response());

        $api->total('domain', $data);
    }

    // /**
    //  * @expectedException \Mailgun\Exception\InvalidArgumentException
    //  */
    // public function testTotalInvalidArgument()
    // {
    //     $api = $this->getApiMock();
    //
    //     $api->total('');
    // }

    public function testAll()
    {
        $data = [
            'foo' => 'bar',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('/v3/domain/stats', $data)
            ->willReturn(new Response());

        $api->all('domain', $data);
    }

    // /**
    //  * @expectedException \Mailgun\Exception\InvalidArgumentException
    //  */
    // public function testAllInvalidArgument()
    // {
    //     $api = $this->getApiMock();
    //
    //     $api->all('');
    // }
}
