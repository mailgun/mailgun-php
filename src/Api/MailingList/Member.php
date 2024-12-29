<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api\MailingList;

use Mailgun\Api\HttpApi;
use Mailgun\Api\Pagination;
use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\MailingList\Member\CreateResponse;
use Mailgun\Model\MailingList\Member\DeleteResponse;
use Mailgun\Model\MailingList\Member\IndexResponse;
use Mailgun\Model\MailingList\Member\ShowResponse;
use Mailgun\Model\MailingList\Member\UpdateResponse;
use Mailgun\Model\MailingList\UpdateResponse as MailingListUpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-mailinglists.html
 */
class Member extends HttpApi
{
    use Pagination;

    /**
     * Returns a paginated list of members of the mailing list.
     * @param  string                   $address        Address of the mailing list
     * @param  int                      $limit          Maximum number of records to return (optional: 100 by default)
     * @param  bool|null                $subscribed     `true` to lists subscribed, `false` for unsubscribed. list all if null
     * @param  array                    $requestHeaders
     * @return IndexResponse
     * @throws ClientExceptionInterface
     */
    public function index(string $address, int $limit = 100, ?bool $subscribed = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);
        Assert::greaterThan($limit, 0);

        $params = [
            'limit' => $limit,
        ];

        if (true === $subscribed) {
            $params['subscribed'] = 'yes';
        } elseif (false === $subscribed) {
            $params['subscribed'] = 'no';
        }

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/pages', $address), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Shows a single member of the mailing list.
     * @param  string                   $list           Address of the mailing list
     * @param  string                   $address        Address of the member
     * @param  array                    $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function show(string $list, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/%s', $list, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates (or updates) a member of the mailing list.
     * @param  string                   $list           Address of the mailing list
     * @param  string                   $address        Address for the member
     * @param  string|null              $name           Name for the member (optional)
     * @param  array                    $vars           Array of field => value pairs to store additional data
     * @param  bool                     $subscribed     `true` to add as subscribed (default), `false` as unsubscribed
     * @param  bool                     $upsert         `true` to update member if present, `false` to raise error in case of a duplicate member (default)
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(
        string $list,
        string $address,
        ?string $name = null,
        array $vars = [],
        ?bool $subscribed = true,
        ?bool $upsert = false,
        array $requestHeaders = []
    ) {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);

        $params = [
            'address' => $address,
            'vars' => \json_encode($vars),
            'subscribed' => $subscribed ? 'yes' : 'no',
            'upsert' => $upsert ? 'yes' : 'no',
        ];

        if (null !== $name) {
            $params['name'] = $name;
        }

        $response = $this->httpPost(sprintf('/v3/lists/%s/members', $list), $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Adds multiple members (up to 1000) to the mailing list.
     * @param string $list Address of the mailing list
     * @param array $members Array of members, each item should be either a single string address or an array of member properties
     * @param bool $upsert `true` to update existing members, `false` (default) to ignore duplicates
     * @param array $requestHeaders
     * @return MailingListUpdateResponse|null
     * @throws ClientExceptionInterface
     */
    public function createMultiple(string $list, array $members, bool $upsert = false, array $requestHeaders = []): ?MailingListUpdateResponse
    {
        Assert::stringNotEmpty($list);
        Assert::isArray($members);

        // workaround for webmozart/asserts <= 1.2
        if (count($members) > 1000) {
            throw new InvalidArgumentException(sprintf('Expected an Array to contain at most %2$d elements. Got: %d', 1000, count($members)));
        }

        foreach ($members as $data) {
            if (is_string($data)) {
                Assert::stringNotEmpty($data);
                // single address - no additional validation required
                continue;
            }

            Assert::isArray($data);

            foreach ($data as $field => &$value) {
                switch ($field) {
                    case 'address':
                        Assert::stringNotEmpty($value);

                        break;
                    case 'vars':
                        if (is_array($value)) {
                            $value = json_encode($value);
                        }
                        break;
                    // We should assert that "vars"'s $value is a string.
                        // no break
                    case 'name':
                        Assert::string($value);
                        break;
                    case 'subscribed':
                        Assert::oneOf($value, ['yes', 'no', true, false]);

                        break;
                }
            }
            unset($value);
        }

        $params = [
            'members' => json_encode($members),
            'upsert' => $upsert ? 'yes' : 'no',
        ];

        $response = $this->httpPost(sprintf('/v3/lists/%s/members.json', $list), $params, $requestHeaders);

        return $this->hydrateResponse($response, MailingListUpdateResponse::class);
    }

    /**
     * Updates a member on the mailing list.
     *
     * @param string $list       Address of the mailing list
     * @param string $address    Address of the member
     * @param array  $parameters Array of key => value pairs to update
     *
     * @return UpdateResponse
     *
     * @throws \Exception
     */
    public function update(string $list, string $address, array $parameters = [], array $requestHeaders = [])
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::isArray($parameters);

        foreach ($parameters as $field => &$value) {
            switch ($field) {
                case 'vars':
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    // We should assert that "vars"'s $value is a string.
                    // no break
                case 'address':
                    Assert::stringNotEmpty($value);

                    break;
                case 'name':
                    Assert::nullOrStringNotEmpty($value);

                    break;
                case 'subscribed':
                    Assert::oneOf($value, ['yes', 'no']);

                    break;
            }
        }
        unset($value);

        if (array_key_exists('name', $parameters) && null === $parameters['name']) {
            unset($parameters['name']);
        }

        $response = $this->httpPut(sprintf('/v3/lists/%s/members/%s', $list, $address), $parameters, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Removes a member from the mailing list.
     * @param  string                   $list           Address of the mailing list
     * @param  string                   $address        Address of the member
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function delete(string $list, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s/members/%s', $list, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
