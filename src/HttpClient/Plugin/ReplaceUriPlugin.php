<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

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
    use Plugin\VersionBridgePlugin;

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
     * @param RequestInterface $request
     * @param callable $next
     * @param callable $first
     * @return mixed
     */
    public function doHandleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request = $request->withUri($this->uri);

        return $next($request);
    }
}
