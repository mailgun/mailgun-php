<?php

namespace Mailgun;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RequestBuilder
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var MultipartStreamBuilder
     */
    private $multipartStreamBuilder;

    /**
     * Creates a new PSR-7 request.
     *
     * @param string            $method
     * @param string            $uri
     * @param array             $headers
     * @param array|string|null $body    Request body. If body is an array we will send a as multipart stream request.
     *                                   If array, each array *item* MUST look like:
     *                                   array (
     *                                   'content' => string|resource|StreamInterface,
     *                                   'name'    => string,
     *                                   'filename'=> string (optional)
     *                                   'headers' => array (optinal) ['header-name' => 'header-value']
     *                                   )
     *
     * @return RequestInterface
     */
    public function create($method, $uri, array $headers = [], $body = null)
    {
        if (!is_array($body)) {
            return $this->requestFactory->createRequest($method, $uri, $headers, $body);
        }

        $builder = $this->getMultipartStreamBuilder();
        foreach ($body as $item) {
            $name = $item['name'];
            $content = $item['content'];
            unset($item['name']);
            unset($item['content']);

            $builder->addResource($name, $content, $item);
        }

        $multipartStream = $builder->build();
        $boundary = $builder->getBoundary();

        $headers['Content-Type'] = 'multipart/form-data; boundary='.$boundary;

        return $this->requestFactory->createRequest($method, $uri, $headers, $multipartStream);
    }

    /**
     * @return RequestFactory
     */
    private function getRequestFactory()
    {
        if ($this->requestFactory === null) {
            $this->requestFactory = MessageFactoryDiscovery::find();
        }

        return $this->requestFactory;
    }

    /**
     * @return MultipartStreamBuilder
     */
    private function getMultipartStreamBuilder()
    {
        if ($this->multipartStreamBuilder === null) {
            $this->multipartStreamBuilder = new MultipartStreamBuilder();
        }

        return $this->multipartStreamBuilder;
    }
}
