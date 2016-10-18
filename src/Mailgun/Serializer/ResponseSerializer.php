<?php

namespace Mailgun\Serializer;

use Psr\Http\Message\ResponseInterface;

/**
 * Serialize a PSR-7 response to something else.
 */
interface ResponseSerializer
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return mixed
     */
    public function deserialze(ResponseInterface $response, $class);
}
