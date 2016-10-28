<?php

namespace Mailgun\Deserializer;

use Psr\Http\Message\ResponseInterface;

/**
 * Do not serialize at all. Just return a PSR-7 response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class PSR7Deserializer implements ResponseDeserializer
{
    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function deserialize(ResponseInterface $response)
    {
        return $response;
    }
}
