<?php

namespace Mailgun\Api;

use Mailgun\Assert;

/**
 * {@link https://documentation.mailgun.com/api-stats.html}.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Stats extends AbstractApi
{
    /**
     * @param string $domain
     * @param array  $params
     *
     * @return array
     */
    public function total($domain, array $params = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->get(sprintf('/v3/%s/stats/total', rawurlencode($domain)), $params);

        return $this->deserializeResp($response);
    }

    /**
     * @param $domain
     * @param array $params
     *
     * @return array
     */
    public function all($domain, array $params = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->get(sprintf('/v3/%s/stats', rawurlencode($domain)), $params);

        return $this->deserializeResp($response);
    }
}
