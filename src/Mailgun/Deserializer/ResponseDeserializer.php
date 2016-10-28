<?php

namespace Mailgun\Deserializer;

use Psr\Http\Message\ResponseInterface;

/**
 * Deserialize a PSR-7 response to something else.
 */
interface ResponseDeserializer
{
    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function deserialize(ResponseInterface $response);
}
