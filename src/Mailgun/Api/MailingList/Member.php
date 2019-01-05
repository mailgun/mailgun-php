<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api\MailingList;

use Mailgun\Api\HttpApi;
use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\MailingList\Member\CreateResponse;
use Mailgun\Model\MailingList\Member\DeleteResponse;
use Mailgun\Model\MailingList\Member\IndexResponse;
use Mailgun\Model\MailingList\Member\ShowResponse;
use Mailgun\Model\MailingList\Member\UpdateResponse;
use Mailgun\Model\MailingList\UpdateResponse as MailingListUpdateResponse;

class Member extends HttpApi
{
    /**
     * Returns a paginated list of members of the mailing list.
     *
     * @param string      $address    Address of the mailing list
     * @param int         $limit      Maximum number of records to return (optional: 100 by default)
     * @param string|null $subscribed `yes` to lists subscribed, `no` for unsubscribed. list all if null
     *
     * @return IndexResponse
     *
     * @throws \Exception
     */
    public function index($address, $limit = 100, $subscribed = null)
    {
        Assert::stringNotEmpty($address);
        Assert::integer($limit);
        Assert::greaterThan($limit, 0);
        Assert::oneOf($subscribed, [null, 'yes', 'no']);

        $params = [
            'limit' => $limit,
            'subscribed' => $subscribed,
        ];

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/pages', $address), $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Shows a single member of the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param string $address Address of the member
     *
     * @return ShowResponse
     *
     * @throws \Exception
     */
    public function show($list, $address)
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/%s', $list, $address));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates (or updates) a member of the mailing list.
     *
     * @param string $list       Address of the mailing list
     * @param string $address    Address for the member
     * @param string $name       Name for the member (optional)
     * @param array  $vars       Array of field => value pairs to store additional data
     * @param string $subscribed `yes` to add as subscribed (default), `no` as unsubscribed
     * @param string $upsert     `yes` to update member if present, `no` to raise error in case of a duplicate member (default)
     *
     * @return CreateResponse
     *
     * @throws \Exception
     */
    public function create($list, $address, $name = null, array $vars = [], $subscribed = 'yes', $upsert = 'no')
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);
        Assert::oneOf($subscribed, ['yes', 'no']);
        Assert::oneOf($upsert, ['yes', 'no']);

        $params = [
            'address' => $address,
            'name' => $name,
            'vars' => $vars,
            'subscribed' => $subscribed,
            'upsert' => $upsert,
        ];

        $response = $this->httpPost(sprintf('/v3/lists/%s/members', $list), $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Adds multiple members (up to 1000) to the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param array  $members Array of members, each item should be either a single string address or an array of member properties
     * @param string $upsert  `yes` to update existing members, `no` (default) to ignore duplicates
     *
     * @return UpdateResponse
     *
     * @throws \Exception
     */
    public function createMultiple($list, array $members, $upsert = 'no')
    {
        Assert::stringNotEmpty($list);
        Assert::isArray($members);

        // workaround for webmozart/asserts <= 1.2
        if (count($members) > 1000) {
            throw new InvalidArgumentException(sprintf('Expected an Array to contain at most %2$d elements. Got: %d',
                1000,
                count($members)
            ));
        }

        Assert::oneOf($upsert, ['yes', 'no']);

        foreach ($members as $data) {
            if (is_string($data)) {
                Assert::stringNotEmpty($data);
                // single address - no additional validation required
                continue;
            }

            Assert::isArray($data);

            foreach ($data as $field => $value) {
                switch ($field) {
                    case 'address':
                        Assert::stringNotEmpty($value);

                        break;
                    case 'name':
                        Assert::string($value);

                        break;
                    case 'vars':
                        Assert::isArray($value);

                        break;
                    case 'subscribed':
                        Assert::oneOf($value, ['yes', 'no']);

                        break;
                }
            }
        }

        $params = [
            'members' => json_encode($members),
            'upsert' => $upsert,
        ];

        $response = $this->httpPost(sprintf('/v3/lists/%s/members.json', $list), $params);

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
    public function update($list, $address, $parameters = [])
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::isArray($parameters);

        foreach ($parameters as $field => $value) {
            switch ($field) {
                case 'address':
                case 'name':
                    Assert::stringNotEmpty($value);

                    break;
                case 'vars':
                    Assert::isArray($value);

                    break;
                case 'subscribed':
                    Assert::oneOf($value, ['yes', 'no']);

                    break;
            }
        }

        $response = $this->httpPut(sprintf('/v3/lists/%s/members/%s', $list, $address), $parameters);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Removes a member from the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param string $address Address of the member
     *
     * @return DeleteResponse
     *
     * @throws \Exception
     */
    public function delete($list, $address)
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s/members/%s', $list, $address));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
