<?php

namespace Mailgun\Api;

use Mailgun\Assert;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait Pagination
{
    abstract protected function httpGet($path, array $parameters = [], array $requestHeaders = []);
    abstract protected function safeDeserialize(ResponseInterface $response, $className);

    /**
     * @param string $url
     * @param string $class
     *
     * @return mixed|null
     */
    public function getPaginationUrl($url, $class)
    {
        Assert::stringNotEmpty($class);

        if (empty($url)) {
            return;
        }

        $response = $this->httpGet($url);

        return $this->safeDeserialize($response, $class);
    }
}
