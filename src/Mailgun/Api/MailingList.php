<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\MailingList\PagesResponse;
use Mailgun\Model\MailingList\CreateResponse;
use Mailgun\Model\MailingList\ShowResponse;
use Mailgun\Model\MailingList\UpdateResponse;
use Mailgun\Model\MailingList\DeleteResponse;
use Mailgun\Model\MailingList\MembersResponse;
use Mailgun\Model\MailingList\CreateMemberResponse;
use Mailgun\Model\MailingList\ShowMemberResponse;
use Mailgun\Model\MailingList\UpdateMemberResponse;
use Mailgun\Model\MailingList\DeleteMemberResponse;
use Psr\Http\Message\ResponseInterface;

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
     * @return PagesResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function pages($limit = 100)
    {
        Assert::integer($limit);

        $params = compact('limit');

        $response = $this->httpGet('/v3/lists/pages', $params);

        return $this->hydrateResponse($response, PagesResponse::class);
    }

    /**
     * Creates a new mailing list on the current domain.
     *
     * @param string $address      Address for the new mailing list
     * @param string $name         Name for the new mailing list (optional)
     * @param string $description  Description for the new mailing list (optional)
     * @param string $access_level List access level, one of: readonly (default), members, everyone
     *
     * @return CreateResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function create($address, $name = null, $description = null, $access_level = 'readonly')
    {
        Assert::stringNotEmpty($address);
        Assert::oneOf($access_level, ['readonly', 'members', 'everyone']);

        $params = compact('address', 'name', 'description', 'access_level');

        $response = $this->httpPost('/v3/lists', $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Returns a single mailing list.
     *
     * @param string $address Address of the mailing list
     *
     * @return ShowResponse|array|ResponseInterface
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
     * @return UpdateResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function update($address, $parameters = [])
    {
        Assert::stringNotEmpty($address);
        Assert::isArray($parameters);

        foreach ($parameters as $field => $value) {
            switch ($field) {
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
     * @return DeleteResponse|array|ResponseInterface
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
     * @param string $address    Address of the mailing list
     * @param int    $limit      Maximum number of records to return (optional: 100 by default)
     * @param mixed  $subscribed `yes` to lists subscribed, `no` for unsubscribed. list all if null
     *
     * @return MembersResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function members($address, $limit = 100, $subscribed = null)
    {
        Assert::stringNotEmpty($address);
        Assert::integer($limit);
        Assert::oneOf($subscribed, [null, 'yes', 'no']);

        $params = compact('limit', 'subscribed');

        $response = $this->httpGet(sprintf('/v3/lists/%s/members/pages', $address), $params);

        return $this->hydrateResponse($response, MembersResponse::class);
    }

    /**
     * Shows a single member of the mailing list.
     *
     * @param string $list    Address of the mailing list
     * @param string $address Address of the member
     *
     * @return ShowMemberResponse|array|ResponseInterface
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
     * @return CreateMemberResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function createMember($list, $address, $name = null, $vars = [], $subscribed = 'yes', $upsert = 'no')
    {
        Assert::stringNotEmpty($list);
        Assert::stringNotEmpty($address);
        Assert::isArray($vars);
        Assert::oneOf($subscribed, ['yes', 'no']);
        Assert::oneOf($upsert, ['yes', 'no']);

        $vars = json_encode($vars);

        $params = compact('address', 'name', 'vars', 'subscribed', 'upsert');

        $response = $this->httpPost(sprintf('/v3/lists/%s/members', $list), $params);

        return $this->hydrateResponse($response, CreateMemberResponse::class);
    }

    /**
     * Updates a member on the mailing list.
     *
     * @param string $list       Address of the mailing list
     * @param string $address    Address of the member
     * @param array  $parameters Array of key => value pairs to update
     *
     * @return UpdateMemberResponse|array|ResponseInterface
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
                case 'vars':
                    $parameters['vars'] = (is_array($value)) ? json_encode($value) : $value;
                    break;
                case 'subscribed':
                    Assert::oneOf($value, ['yes', 'no']);
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
     * @return DeleteMemberResponse|array|ResponseInterface
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