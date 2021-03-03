<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Api\MailingList\Member;
use Mailgun\Assert;
use Mailgun\Model\EmailValidation\ValidateResponse;
use Mailgun\Model\MailingList\CreateResponse;
use Mailgun\Model\MailingList\DeleteResponse;
use Mailgun\Model\MailingList\PagesResponse;
use Mailgun\Model\MailingList\ShowResponse;
use Mailgun\Model\MailingList\UpdateResponse;
use Mailgun\Model\MailingList\ValidationCancelResponse;
use Mailgun\Model\MailingList\ValidationStatusResponse;

/**
 * @see https://documentation.mailgun.com/en/latest/api-mailinglists.html
 */
class MailingList extends HttpApi
{
    public function member(): Member
    {
        return new Member($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * Returns a paginated list of mailing lists on the domain.
     *
     * @param int $limit Maximum number of records to return (optional: 100 by default)
     *
     * @return PagesResponse
     *
     * @throws \Exception
     */
    public function pages(int $limit = 100)
    {
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet('/v3/lists/pages', $params);

        return $this->hydrateResponse($response, PagesResponse::class);
    }

    /**
     * Creates a new mailing list on the current domain.
     *
     * @param string $address     Address for the new mailing list
     * @param string $name        Name for the new mailing list (optional)
     * @param string $description Description for the new mailing list (optional)
     * @param string $accessLevel List access level, one of: readonly (default), members, everyone
     *
     * @return CreateResponse
     *
     * @throws \Exception
     */
    public function create(string $address, string $name = null, string $description = null, string $accessLevel = 'readonly', string $replyPreference = 'list')
    {
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);
        Assert::nullOrStringNotEmpty($description);
        Assert::oneOf($accessLevel, ['readonly', 'members', 'everyone']);
        Assert::oneOf($replyPreference, ['list', 'sender']);

        $params = [
            'address' => $address,
            'name' => $name,
            'description' => $description,
            'access_level' => $accessLevel,
            'reply_preference' => $replyPreference,
        ];

        $response = $this->httpPost('/v3/lists', $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Returns a single mailing list.
     *
     * @param string $address Address of the mailing list
     *
     * @return ShowResponse
     *
     * @throws \Exception
     */
    public function show(string $address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s', $address));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Updates a mailing list.
     *
     * @param string $address    Address of the mailing list
     * @param array  $parameters Array of field => value pairs to update
     *
     * @return UpdateResponse
     *
     * @throws \Exception
     */
    public function update(string $address, array $parameters = [])
    {
        Assert::stringNotEmpty($address);
        Assert::isArray($parameters);

        foreach ($parameters as $field => $value) {
            switch ($field) {
                case 'address':
                case 'name':
                case 'description':
                    Assert::stringNotEmpty($value);

                    break;
                case 'access_level':
                    Assert::oneOf($value, ['readonly', 'members', 'everyone']);

                    break;
            }
        }

        $response = $this->httpPut(sprintf('/v3/lists/%s', $address), $parameters);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Removes a mailing list from the domain.
     *
     * @param string $address Address of the mailing list
     *
     * @return DeleteResponse
     *
     * @throws \Exception
     */
    public function delete(string $address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s', $address));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Validates mailing list.
     *
     * @param string $address Address of the mailing list
     *
     * @return ValidateResponse
     *
     * @throws \Exception
     */
    public function validate(string $address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpPost(sprintf('/v3/lists/%s/validate', $address));

        return $this->hydrateResponse($response, ValidateResponse::class);
    }

    /**
     * Get mailing list validation status.
     *
     * @param string $address Address of the mailing list
     *
     * @return ValidationStatusResponse
     *
     * @throws \Exception
     */
    public function getValidationStatus(string $address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s/validate', $address));

        return $this->hydrateResponse($response, ValidationStatusResponse::class);
    }

    /**
     * Cancel mailing list validation.
     *
     * @param string $address Address of the mailing list
     *
     * @return ValidationCancelResponse
     *
     * @throws \Exception
     */
    public function cancelValidation(string $address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s/validate', $address));

        return $this->hydrateResponse($response, ValidationCancelResponse::class);
    }
}
