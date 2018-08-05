<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Webhook;
use Mailgun\Model\Webhook\IndexResponse;
use Mailgun\Model\Webhook\ShowResponse;
use Mailgun\Model\Webhook\CreateResponse;
use Mailgun\Model\Webhook\DeleteResponse;
use Mailgun\Model\Webhook\UpdateResponse;

class WebhookTest extends TestCase
{
    protected function getApiClass()
    {
        return Webhook::class;
    }

    public function testVerifyWebhookGood()
    {
        $api = $this->getApiInstance('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');

        $timestamp = '1403645220';
        $token = '5egbgr1vjgqxtrnp65xfznchgdccwh5d6i09vijqi3whgowmn6';
        $signature = '9cfc5c41582e51246e73c88d34db3af0a3a2692a76fbab81492842f000256d33';

        $this->assertTrue($api->verifyWebhookSignature($timestamp, $token, $signature));
    }

    public function testVerifyWebhookBad()
    {
        $api = $this->getApiInstance('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');
        $timestamp = '1403645220';
        $token = 'owyldpe6nxhmrn78epljl6bj0orrki1u3d2v5e6cnlmmuox8jr';
        $signature = '9cfc5c41582e51246e73c88d34db3af0a3a2692a76fbab81492842f000256d33';

        $this->assertFalse($api->verifyWebhookSignature($timestamp, $token, $signature));
    }

    public function testVerifyWebhookEmptyRequest()
    {
        $api = $this->getApiInstance('key-3ax6xnjp29jd6fds4gc373sgvjxteol0');

        $this->assertFalse($api->verifyWebhookSignature('', '', ''));
    }

    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/webhooks');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance('key');
        $api->index('example.com');
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/webhooks/hook_1');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance('key');
        $api->show('example.com', 'hook_1');
    }

    public function testCreate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains/example.com/webhooks');
        $this->setHydrateClass(CreateResponse::class);
        $this->setRequestBody([
            'id' => '4711',
            'url' => 'url',
        ]);

        $api = $this->getApiInstance('key');
        $api->create('example.com', '4711', 'url');
    }

    public function testUpdate()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/webhooks/4711');
        $this->setHydrateClass(UpdateResponse::class);
        $this->setRequestBody([
            'url' => 'url',
        ]);

        $api = $this->getApiInstance('key');
        $api->update('example.com', '4711', 'url');
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com/webhooks/4711');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance('key');
        $api->delete('example.com', '4711');
    }
}
