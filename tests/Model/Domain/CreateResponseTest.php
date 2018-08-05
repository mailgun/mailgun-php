<?php

declare(strict_types=1);

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\CredentialResponse;
use Mailgun\Tests\Model\BaseModelTest;

class CreateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<JSON
{
  "domain": {
    "name": "example.com",
    "created_at": "Fri, 22 Nov 2013 18:42:33 GMT",
    "wildcard": false,
    "spam_action": "disabled",
    "smtp_login": "postmaster@example.com",
    "smtp_password": "thiswontwork",
    "state": "active"
  },
  "receiving_dns_records": [
    {
      "priority": "10",
      "record_type": "MX",
      "valid": "valid",
      "value": "mxa.mailgun.org"
    },
    {
      "priority": "10",
      "record_type": "MX",
      "valid": "valid",
      "value": "mxb.mailgun.org"
    }
  ],
  "message": "Domain has been created",
  "sending_dns_records": [
    {
      "record_type": "TXT",
      "valid": "valid",
      "name": "example.com",
      "value": "v=spf1 include:mailgun.org ~all"
    },
    {
      "record_type": "TXT",
      "valid": "valid",
      "name": "k1._domainkey.example.com",
      "value": "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4G...."
    },
    {
      "record_type": "CNAME",
      "valid": "valid",
      "name": "email.example.com",
      "value": "mailgun.org"
    }
  ]
}

JSON;
        $model = CreateResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertNotEmpty($model->getDomain());
        $this->assertNotEmpty($model->getInboundDNSRecords());
        $this->assertNotEmpty($model->getOutboundDNSRecords());
    }
}
