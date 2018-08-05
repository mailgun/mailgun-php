<?php

declare(strict_types=1);

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CredentialResponse;
use Mailgun\Model\Domain\CredentialResponseItem;
use Mailgun\Tests\Model\BaseModelTest;

class CredentialResponseItemTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<JSON
{
  "size_bytes": 5,
  "created_at": "Tue, 27 Sep 2011 20:24:22 GMT",
  "mailbox": "user@samples.mailgun.org",
  "login": "user"
}
JSON;
        $model = CredentialResponseItem::create(json_decode($json, true));
        $this->assertEquals('user', $model->getLogin());
        $this->assertEquals('user@samples.mailgun.org', $model->getMailbox());
        $this->assertEquals('5', $model->getSizeBytes());
        $this->assertEquals(new \DateTime('Tue, 27 Sep 2011 20:24:22 GMT'), $model->getCreatedAt());
    }
}
