<?php

namespace Mailgun\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Replaces a URI with a new one. Good for debugging.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ReplaceUriPlugin implements Plugin
{
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request = $request->withUri($this->uri);

        return $next($request);
    }
}
