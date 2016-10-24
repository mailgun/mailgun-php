<?php

namespace Mailgun\Serializer;

use Mailgun\Exception\SerializeException;
use Psr\Http\Message\ResponseInterface;

/**
 * Serialize an HTTP response to array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ArrayDeserializer implements ResponseDeserializer
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return array
     */
    public function deserialize(ResponseInterface $response, $class)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            throw new SerializeException('The ArraySerializer cannot deserialize response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        $content = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new SerializeException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return $content;
    }
}
