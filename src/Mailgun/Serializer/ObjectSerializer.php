<?php

namespace Mailgun\Serializer;

use Mailgun\Exception\SerializeException;
use Mailgun\Resource\CreatableFromArray;
use Psr\Http\Message\ResponseInterface;

/**
 * Serialize an HTTP response to domain object.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ObjectSerializer implements ResponseSerializer
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return ResponseInterface
     */
    public function deserialze(ResponseInterface $response, $class)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            throw new SerializeException('The ObjectSerializer cannot deserialize response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new SerializeException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            $object = call_user_func($class.'::createFromArray', $data);
        } else {
            $object = new $class($data);
        }

        return $object;
    }
}
