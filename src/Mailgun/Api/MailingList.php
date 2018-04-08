<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\MailingList\Response\CreateMemberResponse;
use Mailgun\Model\MailingList\Response\CreateResponse;
use Mailgun\Model\MailingList\Response\DeleteMemberResponse;
use Mailgun\Model\MailingList\Response\DeleteResponse;
use Mailgun\Model\MailingList\Response\MembersResponse;
use Mailgun\Model\MailingList\Response\PagesResponse;
use Mailgun\Model\MailingList\Response\ShowMemberResponse;
use Mailgun\Model\MailingList\Response\ShowResponse;
use Mailgun\Model\MailingList\Response\UpdateMemberResponse;
use Mailgun\Model\MailingList\Response\UpdateResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
class MailingList extends HttpApi
{
    /**
     * Returns a paginated list of mailing lists on the domain.
     *
     * @param int $limit Maximum number of records to return (optional: 100 by default)
     *
     * @return PagesResponse
     *
     * @throws \Exception
     */
    public function pages($limit = 100)
    {
        Assert::integer($limit);
        Assert::greaterThan($limit, 0);

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
    public function create($address, $name = null, $description = null, $accessLevel = 'readonly')
    {
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);
        Assert::nullOrStringNotEmpty($description);
        Assert::oneOf($accessLevel, ['readonly', 'members', 'everyone']);

        $params = [
            'address' => $address,
            'name' => $name,
            'description' => $description,
            'access_level' => $accessLevel,
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
    public function show($address)
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
    public function update($address, $parameters = [])
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
                default:
                    throw new InvalidArgumentException(sprintf('unknown parameter "%s"', $field));
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
    public function delete($address)
    {
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s', $address));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Returns a paginated list of members of the mailing list.
     *
     * @param string      $address    Address of the mailing list
     * @param int         $limit      Maximum number of records to return (optional: 100 by default)
     * @param string|null $subscribed `yes` to lists subscribed, `no` for unsubscribed. list all if null
     *
     * @return MembersResponse
     *
     * @throws \Exception
     */
    public function members($address, $limit = 100, $subscribed = null)
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

        return $this->hydrateResponse($response, MembersResponse::class);
    }

    /**
     * Shows a single member of the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param string $address Address of the member
     *
     * @return ShowMemberResponse
     *
     * @throws \Exception
     */
    public function showMember($list, $address)
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/%s', $list, $address));

        return $this->hydrateResponse($response, ShowMemberResponse::class);
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
     * @return CreateMemberResponse
     *
     * @throws \Exception
     */
    public function createMember($list, $address, $name = null, $vars = [], $subscribed = 'yes', $upsert = 'no')
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($name);
        Assert::isArray($vars);
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

        return $this->hydrateResponse($response, CreateMemberResponse::class);
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
    public function addMembers($list, array $members, $upsert = 'no')
    {
        Assert::stringNotEmpty($list);
        Assert::isArray($members);
        Assert::maxCount($members, 1000);
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
                    default:
                        throw new InvalidArgumentException(sprintf('unknown parameter "%s"', $field));
                        break;
                }
            }
        }

        $params = [
            'members' => json_encode($members),
            'upsert' => $upsert,
        ];

        $response = $this->httpPost(sprintf('/v3/lists/%s/members.json', $list), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Updates a member on the mailing list.
     *
     * @param string $list       Address of the mailing list
     * @param string $address    Address of the member
     * @param array  $parameters Array of key => value pairs to update
     *
     * @return UpdateMemberResponse
     *
     * @throws \Exception
     */
    public function updateMember($list, $address, $parameters = [])
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
                default:
                    throw new InvalidArgumentException(sprintf('unknown parameter "%s"', $field));
                    break;
            }
        }

        $response = $this->httpPut(sprintf('/v3/lists/%s/members/%s', $list, $address), $parameters);

        return $this->hydrateResponse($response, UpdateMemberResponse::class);
    }

    /**
     * Removes a member from the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param string $address Address of the member
     *
     * @return DeleteMemberResponse
     *
     * @throws \Exception
     */
    public function deleteMember($list, $address)
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/lists/%s/members/%s', $list, $address));

        return $this->hydrateResponse($response, DeleteMemberResponse::class);
    }
}
