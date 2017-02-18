<?php

namespace Mailgun\Resource\Api\Tag;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class UpdateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @param string $message
     */
    private function __construct($message)
    {
        $this->message = $message;
    }

    public static function create(array $data)
    {
        return new self($data['message']);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
