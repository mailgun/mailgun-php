<?php

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class DeleteCredentialResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $spec;

    /**
     * @param string $message
     */
    private function __construct($message, $spec)
    {
        $this->message = $message;
        $this->spec = $spec;
    }

    /**
     * @param array $data
     *
     * @return DeleteCredentialResponse
     */
    public static function create(array $data)
    {
        return new self($data['message'], $data['spec']);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getSpec()
    {
        return $this->spec;
    }
}
