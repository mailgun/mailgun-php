<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\EmailValidationV4;
use Mailgun\Model\EmailValidationV4\CreateBulkJobResponse;
use Mailgun\Model\EmailValidationV4\CreateBulkPreviewResponse;
use Mailgun\Model\EmailValidationV4\DeleteBulkJobResponse;
use Mailgun\Model\EmailValidationV4\GetBulkJobResponse;
use Mailgun\Model\EmailValidationV4\GetBulkJobsResponse;
use Mailgun\Model\EmailValidationV4\GetBulkPreviewResponse;
use Mailgun\Model\EmailValidationV4\GetBulkPreviewsResponse;
use Mailgun\Model\EmailValidationV4\Job;
use Mailgun\Model\EmailValidationV4\JobDownloadUrl;
use Mailgun\Model\EmailValidationV4\Preview;
use Mailgun\Model\EmailValidationV4\PromoteBulkPreviewResponse;
use Mailgun\Model\EmailValidationV4\Summary;
use Mailgun\Model\EmailValidationV4\ValidateResponse;

class EmailValidationV4Test extends TestCase
{
    protected function getApiClass()
    {
        return EmailValidationV4::class;
    }

    protected function getApiInstance($apiKey = null): EmailValidationV4
    {
        return parent::getApiInstance($apiKey);
    }

    public function testInvalidEmail()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate?address=email@example.com&provider_lookup=1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "address": "email@example.com",
    "did_you_mean": "email@domain.com",
    "is_disposable_address": true,
    "is_role_address": false,
    "reason": ["no_data"],
    "result": "undeliverable",
    "risk": "high"
}
JSON
        ));

        $api = $this->getApiInstance();

        /**
         * @var ValidateResponse
         */
        $response = $api->validate('email@example.com', true);

        $this->assertInstanceOf(ValidateResponse::class, $response);
        $this->assertEquals('email@example.com', $response->getAddress());
        $this->assertEquals('email@domain.com', $response->getDidYouMean());
        $this->assertTrue($response->isDisposableAddress());
        $this->assertFalse($response->isRoleAddress());
        $this->assertEquals(['no_data'], $response->getReason());
        $this->assertEquals('undeliverable', $response->getResult());
        $this->assertEquals('high', $response->getRisk());
        $this->assertNull($response->getRootAddress());
    }

    public function testValidEmail()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate?address=email3@example.com&provider_lookup=0');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "address": "email3@example.com",
    "is_disposable_address": false,
    "is_role_address": true,
    "reason": [],
    "result": "deliverable",
    "risk": "low",
    "root_address": "email2@example.com"
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var ValidateResponse
         */
        $response = $api->validate('email3@example.com', false);

        $this->assertInstanceOf(ValidateResponse::class, $response);
        $this->assertEquals('email3@example.com', $response->getAddress());
        $this->assertNull($response->getDidYouMean());
        $this->assertFalse($response->isDisposableAddress());
        $this->assertTrue($response->isRoleAddress());
        $this->assertEmpty($response->getReason());
        $this->assertEquals('deliverable', $response->getResult());
        $this->assertEquals('low', $response->getRisk());
        $this->assertEquals('email2@example.com', $response->getRootAddress());
    }

    public function testGetBulkJobs()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate/bulk?limit=50');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "jobs": [
        {
            "created_at": 1590080191,
            "download_url": {
                "csv": "<download_link>",
                "json": "<download_link>"
            },
            "id": "bulk_validations_sandbox2_mailgun_org",
            "quantity": 207665,
            "records_processed": 207665,
            "status": "uploaded",
            "summary": {
                "result": {
                    "deliverable": 181854,
                    "do_not_send": 5647,
                    "undeliverable": 12116,
                    "catch_all": 2345,
                    "unknown": 5613
                },
                "risk": {
                    "high": 17763,
                    "low": 142547,
                    "medium": 41652,
                    "unknown": 5613
                }
            }
        },
        {
            "created_at": 1590080191,
            "download_url": {
                "csv": "<download_link>",
                "json": "<download_link>"
            },
            "id": "bulk_validations_sandbox_mailgun_org",
            "quantity": 207,
            "records_processed": 207,
            "status": "uploaded",
            "summary": {
                "result": {
                    "deliverable": 181854,
                    "do_not_send": 5647,
                    "undeliverable": 12116,
                    "catch_all": 2345,
                    "unknown": 5613
                },
                "risk": {
                    "high": 17763,
                    "low": 142547,
                    "medium": 41652,
                    "unknown": 5613
                }
            }
        }
    ],
    "total": 2,
    "paging": {
      "next": "https://url_to_next_page",
      "previous": "https://url_to_previous_page",
      "first": "https://url_to_first_page",
      "last": "https://url_to_last_page"
    }
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var GetBulkJobsResponse
         */
        $response = $api->getBulkJobs(50);

        $this->assertInstanceOf(GetBulkJobsResponse::class, $response);
        $this->assertCount(2, $response->getJobs());
        $this->assertContainsOnlyInstancesOf(Job::class, $response->getJobs());
        $this->assertTrue(method_exists($response, 'getNextUrl'));
        $this->assertTrue(method_exists($response, 'getPreviousUrl'));
        $this->assertTrue(method_exists($response, 'getFirstUrl'));
        $this->assertTrue(method_exists($response, 'getLastUrl'));
    }

    public function testGetBulkJob()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate/bulk/listId123');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "created_at": 1590080191,
    "download_url": {
        "csv": "<download_link>",
        "json": "<download_link>"
    },
    "id": "bulk_validations_sandbox_mailgun_org",
    "quantity": 207,
    "records_processed": 208,
    "status": "uploaded",
    "summary": {
        "result": {
            "deliverable": 181854,
            "do_not_send": 5647,
            "undeliverable": 12116,
            "catch_all": 2345,
            "unknown": 5613
        },
        "risk": {
            "high": 17763,
            "low": 142547,
            "medium": 41652,
            "unknown": 5613
        }
    }
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var GetBulkJobResponse
         */
        $response = $api->getBulkJob('listId123');

        $this->assertInstanceOf(GetBulkJobResponse::class, $response);
        $this->assertInstanceOf(Job::class, $response);
        $this->assertEquals('2020-05-21 16:56:31', $response->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(JobDownloadUrl::class, $response->getDownloadUrl());
        $this->assertEquals('bulk_validations_sandbox_mailgun_org', $response->getId());
        $this->assertEquals(207, $response->getQuantity());
        $this->assertEquals(208, $response->getRecordsProcessed());
        $this->assertEquals('uploaded', $response->getStatus());
        $this->assertInstanceOf(Summary::class, $response->getSummary());
    }

    public function testDeleteBulkJob()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v4/address/validate/bulk/listId321');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
     "message": "Validation job canceled."
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var DeleteBulkJobResponse
         */
        $response = $api->deleteBulkJob('listId321');

        $this->assertInstanceOf(DeleteBulkJobResponse::class, $response);
        $this->assertEquals('Validation job canceled.', $response->getMessage());
    }

    public function testCreateBulkJob()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/address/validate/bulk/listId1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "id":"listId1",
    "message": "The validation job was submitted."
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var CreateBulkJobResponse
         */
        $response = $api->createBulkJob('listId1', __FILE__);

        $this->assertInstanceOf(CreateBulkJobResponse::class, $response);
        $this->assertEquals('listId1', $response->getId());
        $this->assertEquals('The validation job was submitted.', $response->getMessage());
    }

    public function testGetBulkPreviews()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate/preview?limit=50');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "previews": [
    {
      "id": "test_500",
      "valid": true,
      "status": "preview_complete",
      "quantity": 8,
      "created_at": 1590080191,
      "summary": {
        "result": {
          "deliverable": 37.5,
          "do_not_send": 0,
          "undeliverable": 23,
          "catch_all": 2,
          "unknown": 37.5
        },
        "risk": {
          "high": 25,
          "low": 25,
          "medium": 12.5,
          "unknown": 37.5
        }
      }
    },
    {
      "id": "test_501",
      "valid": true,
      "status": "preview_complete",
      "quantity": 8,
      "created_at": 1590155015,
      "summary": {
        "result": {
          "deliverable": 37.5,
          "do_not_send": 0,
          "undeliverable": 23,
          "catch_all": 2,
          "unknown": 37.5
        },
        "risk": {
          "high": 25,
          "low": 25,
          "medium": 12.5,
          "unknown": 37.5
        }
      }
    }
  ]
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var GetBulkPreviewsResponse
         */
        $response = $api->getBulkPreviews(50);

        $this->assertInstanceOf(GetBulkPreviewsResponse::class, $response);
        $this->assertCount(2, $response->getPreviews());
        $this->assertContainsOnlyInstancesOf(Preview::class, $response->getPreviews());
    }

    public function testGetBulkPreview()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/address/validate/preview/test_500');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "preview": {
    "id": "test_500",
    "valid": true,
    "status": "preview_complete",
    "quantity": 8,
    "created_at": 1590080191,
    "summary": {
      "result": {
        "deliverable": 37.5,
        "undeliverable": 23,
        "catch_all": 2,
        "unknown": 37.5
      },
      "risk": {
        "high": 25,
        "low": 25,
        "medium": 12.5,
        "unknown": 37.5
      }
    }
  }
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var GetBulkPreviewResponse
         */
        $response = $api->getBulkPreview('test_500');

        $this->assertInstanceOf(GetBulkPreviewResponse::class, $response);
        $this->assertInstanceOf(Preview::class, $response->getPreview());
    }

    public function testDeleteBulkPreview()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v4/address/validate/preview/previewId1');
        $this->setHttpResponse(new Response(204, ['Content-Type' => 'application/json']));
        $api = $this->getApiInstance();

        /**
         * @var bool
         */
        $status = $api->deleteBulkPreview('previewId1');

        $this->assertTrue($status);
    }

    public function testPromoteBulkPreview()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v4/address/validate/preview/previewId2');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
     "message": "Validation preview promoted."
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var PromoteBulkPreviewResponse
         */
        $response = $api->promoteBulkPreview('previewId2');

        $this->assertInstanceOf(PromoteBulkPreviewResponse::class, $response);
    }

    public function testCreateBulkPreview()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/address/validate/preview/preview3');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
    "id":"preview3",
    "message": "The bulk preview was submitted."
}
JSON
        ));
        $api = $this->getApiInstance();

        /**
         * @var CreateBulkPreviewResponse
         */
        $response = $api->createBulkPreview('preview3', __FILE__);

        $this->assertInstanceOf(CreateBulkPreviewResponse::class, $response);
        $this->assertEquals('preview3', $response->getId());
        $this->assertEquals('The bulk preview was submitted.', $response->getMessage());
    }
}
