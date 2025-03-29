<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Api;

use Mailgun\Api\DomainKeys;
use Mailgun\Model\Domain\DomainKeyResponse;
use Mailgun\Tests\Api\TestCase;
use Nyholm\Psr7\Response;

class DomainKeysTest extends TestCase
{
    /**
     * @return DomainKeys
     */
    protected function getApiClass(): string
    {
        return DomainKeys::class;
    }

    public function testListDomainKeys()
    {
        $domain = 'samples.mailgun.org';
        $this->setRequestMethod('GET');
        $this->setRequestUri(sprintf('/v4/domains/%s/keys', $domain));
        $this->setHttpResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                <<<JSON
{
    "items": [
        {
            "signing_domain": "samples.mailgun.org",
            "selector": "miha",
            "dns_record": {
                "is_active": null,
                "cached": [],
                "name": "miha._domainkey.sandbox17c69dbd4f6c4794bf7143cce61be31f.mailgun.org",
                "record_type": "TXT",
                "valid": "unknown",
                "value": "k=rsa; p=xxx+k1jwe2fhrL/5QH/D6pBTQNcn9cQeJWPvtxBakoJAY5CpcSuQGQQphB047gT6UXmT/u5hC86GV1QFCmPTHmUb5AUoNWSAs3YE4sYW+iHAWsmbVsxODCwH08k0m4ZNOT7UlKBR8mwIDAQAB"
            }
        },
        {
            "signing_domain": "samples.mailgun.org",
            "selector": "key-1743165390",
            "dns_record": {
                "is_active": null,
                "cached": [],
                "name": "key-1743165390._domainkey.sandbox17c69dbd4f6c4794bf7143cce61be31f.mailgun.org",
                "record_type": "TXT",
                "valid": "unknown",
                "value": "k=rsa; p=xxx+8Dk7XYL4wiFHK960gCz7rCkXWfkAB+sxqkX1X8fUIVphMQ+mv2yh/S6ADssWqj9+zRMc5n51Lu6oWAXhd4H2PvlrnO3RNSPzCC8jWm8Wp+tfnMcmBxHW0gGWZv5F7JEHVHaa/nzIPK3rMSOvdV8iCTpPwInP+qwIDAQAB"
            }
        }
    ]
}
JSON

            )
        );
        $api = $this->getApiInstance();
        /**
         * @var DomainKeyResponse $response
         */
        $response = $api->listDomainKeys($domain);
        $this->assertInstanceOf(DomainKeyResponse::class, $response);
        $this->assertEquals(2, count($response->getItems()));
        foreach ($response->getItems() as $item) {
            $this->assertInstanceOf(DomainKeyResponse::class, $item);
        }
    }

    public function testCreateDomainKey()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v1/dkim/keys');
        $this->setRequestBody(
            [
            'signing_domain' => 'example.com',
            'selector' => 'foo',
            ]
        );
        $this->setHttpResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                <<<JSON
{
    "signing_domain": "sandbox17c69dbd4f6c4794bf7143cce61be31f.mailgun.org",
    "selector": "key-1743174799",
    "dns_record": {
        "is_active": null,
        "cached": [],
        "name": "key-1743174799._domainkey.sandbox17c69dbd4f6c4794bf7143cce61be31f.mailgun.org",
        "record_type": "TXT",
        "valid": "unknown",
        "value": "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCq5IXjjE0RIrH8eFavDG+WqinlgUFIdZS0buS1p0nLm8X/SomXJgyaEppV8WUY5DOdmxMMLwVrynL6/QGH2TkQI8q7tiy7MLfrd6kDihoqEytj5omqEBjmZy0UGd0OvaZ7Kt53i2xVSxYPjIDRp3UsgDvEzYTQCrrHe1x5yuCZOwIDAQAB"
    }
}

JSON
            )
        );
        $this->setHydrateClass(DomainKeyResponse::class);

        /**
         * @var DomainKeyResponse $response
         */
        $api = $this->getApiInstance();
        $res = $api->createDomainKey('example.com', 'foo');
        $this->assertInstanceOf(DomainKeyResponse::class, $res);
    }
}
