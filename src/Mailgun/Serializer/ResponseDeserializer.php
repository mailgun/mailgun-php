<?php

namespace Mailgun\Serializer;

use Psr\Http\Message\ResponseInterface;

/**
 * Deserialize a PSR-7 response to something else.
 */
interface ResponseDeserializer
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return mixed
     */
    public function deserialize(ResponseInterface $response, $class);
}
