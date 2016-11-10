<?php

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class UpdateCredentialResponse implements ApiResponse
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

    /**
     * @param array $data
     *
     * @return UpdateCredentialResponse
     */
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
