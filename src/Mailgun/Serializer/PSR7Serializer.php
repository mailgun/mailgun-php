<?php

namespace Mailgun\Serializer;

use Psr\Http\Message\ResponseInterface;

/**
 * Do not serialize at all. Just return a PSR-7 response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class PSR7Serializer implements ResponseSerializer
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return ResponseInterface
     */
    public function deserialze(ResponseInterface $response, $class)
    {
        return $response;
    }
}
