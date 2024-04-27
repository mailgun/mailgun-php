<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Exception;
use Mailgun\Api\MailingList\Member;
use Mailgun\Assert;
use Mailgun\Model\EmailValidation\ValidateResponse;
use Mailgun\Model\MailingList\BulkResponse;
use Mailgun\Model\MailingList\CreateResponse;
use Mailgun\Model\MailingList\DeleteResponse;
use Mailgun\Model\MailingList\PagesResponse;
use Mailgun\Model\MailingList\ShowResponse;
use Mailgun\Model\MailingList\UpdateResponse;
use Mailgun\Model\MailingList\ValidationCancelResponse;
use Mailgun\Model\MailingList\ValidationStatusResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-mailinglists.html
 */
class MailingList extends HttpApi
{
    /**
     * @return Member
     */
    public function member(): Member
    {
        return new Member($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * Returns a paginated list of mailing lists on the domain.
     * @param  int                      $limit          Maximum number of records to return (optional: 100 by default)
     * @param  array                    $requestHeaders
     * @return PagesResponse
     * @throws ClientExceptionInterface
     */
    public function pages(int $limit = 100, array $requestHeaders = [])
    {
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet('/v3/lists/pages', $params, $requestHeaders);

        return $this->hydrateResponse($response, PagesResponse::class);
    }

    /**
     * Creates a new mailing list on the current domain.
     * @param  string                   $address         Address for the new mailing list
     * @param  string|null              $name            Name for the new mailing list (optional)
     * @param  string|null              $description     Description for the new mailing list (optional)
     * @param  string                   $accessLevel     List access level, one of: readonly (default), members, everyone
     * @param  string                   $replyPreference Set where replies should go: list (default) | sender (optional)
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(
        string $address,
        ?string $name = null,
        ?string $description = null,
        string $accessLevel = 'readonly',
        string $replyPreference = 'list',
        array $requestHeaders = []
    ) {
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);
        Assert::nullOrStringNotEmpty($description);
        Assert::oneOf($accessLevel, ['readonly', 'members', 'everyone']);
        Assert::oneOf($replyPreference, ['list', 'sender']);

        $params = [
            'address' => $address,
            'access_level' => $accessLevel,
            'reply_preference' => $replyPreference,
        ];
        $description ? $params['description'] = $description : false;
        $name ? $params['name'] = $name : false;

        $response = $this->httpPost('/v3/lists', $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Returns a single mailing list.
     * @param  string                   $address        Address of the mailing list
     * @param  array                    $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function show(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s', $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Updates a mailing list.
     * @param  string                   $address        Address of the mailing list
     * @param  array                    $parameters     Array of field => value pairs to update
     * @param  array                    $requestHeaders
     * @return UpdateResponse
     * @throws ClientExceptionInterface
     */
    public function update(string $address, array $parameters = [], array $requestHeaders = [])
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

        $response = $this->httpPut(sprintf('/v3/lists/%s', $address), $parameters, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Removes a mailing list from the domain.
     *
     * @param  string                             $address Address of the mailing list
     * @return DeleteResponse
     * @throws Exception|ClientExceptionInterface
     */
    public function delete(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s', $address), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Validates mailing list.
     * @param  string                   $address        Address of the mailing list
     * @param  array                    $requestHeaders
     * @return ValidateResponse
     * @throws ClientExceptionInterface
     */
    public function validate(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpPost(sprintf('/v3/lists/%s/validate', $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ValidateResponse::class);
    }

    /**
     * Get mailing list validation status.
     * @param  string                   $address        Address of the mailing list
     * @param  array                    $requestHeaders
     * @return ValidationStatusResponse
     * @throws ClientExceptionInterface
     */
    public function getValidationStatus(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s/validate', $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ValidationStatusResponse::class);
    }

    /**
     * Cancel mailing list validation.
     * @param  string                   $address        Address of the mailing list
     * @param  array                    $requestHeaders
     * @return ValidationCancelResponse
     * @throws ClientExceptionInterface
     */
    public function cancelValidation(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s/validate', $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ValidationCancelResponse::class);
    }

    /**
     * Bulk upload members to a mailing list (JSON)
     * @param string $mailList
     * @param array $members
     * @param bool $isUpsert
     * @param array $requestHeaders
     * @return BulkResponse
     * @throws ClientExceptionInterface
     */
    public function bulkUploadJson(string $mailList, array $members, bool $isUpsert = false, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($mailList);

        $query = [
            'members' => json_encode($members),
            'upsert' => $isUpsert,
        ];

        $response = $this->httpPost(
            sprintf('/v3/lists/%s/members.json?%s', $mailList, http_build_query($query)),
            [],
            $requestHeaders
        );

        return $this->hydrateResponse($response, BulkResponse::class);
    }

    /**
     * Bulk upload members to a mailing list (CSV)
     * //TODO
     * @param string $mailList
     * @param array $members
     * @param bool $isUpsert
     * @param array $requestHeaders
     * @return BulkResponse
     * @throws ClientExceptionInterface
     */
    public function bulkUploadCsv(string $mailList, array $members, bool $isUpsert = false, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($mailList);

        $payload = [
            'members' => implode(",", $members),
            'upsert' => $isUpsert ? 'true' : 'false'
        ];

        $response = $this->httpPost(
            sprintf('/v3/lists/%s/members.csv', $mailList),
            $payload,
            $requestHeaders
        );

        return $this->hydrateResponse($response, BulkResponse::class);
    }
}
