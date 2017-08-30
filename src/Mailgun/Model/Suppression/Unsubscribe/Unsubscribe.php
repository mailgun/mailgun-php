<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Unsubscribe;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Unsubscribe
{
    /**
     * The unsubscribe event identifier
     * 
     * @var string
     */
    private $id = NULL;
    
    /**
     * The unsubscribe email address
     * @var string
     */
    private $address = NULL;

    /**
     * Tag to unsubscribe from, use * to unsubscribe address from domain
     * 
     * Note: If NULL the value means * (all for a domain)
     * 
     * @var string
     */
    private $tag = NULL;

    /**
     * The creation date for the event
     * 
     * @var \DateTime
     */
    private $createdAt = NULL;

    /**
     * @param string $address
     */
    private function __construct($address = NULL)
    {
        $this->address = $address;
        $this->createdAt = new \DateTime();
    }

    /**
     * Create a Unsubscribe object based in info provided in $data array
     * 
     * Fields allowed:
     * 
     * - id: The unsubscribe event identifier
     * - address: The unsubscribe email address
     * - tag: Tag to unsubscribe (use * or NULL to unsubscribe address from domain)
     * - created_at: The creation date for the event
     * 
     * @param array $data the info provided
     *
     * @return Unsubscribe
     */
    public static function create(array $data)
    {
        $unsubscribe = new self($data['address']);

        if (isset($data['id'])) {
            $unsubscribe->setId($data['id']);
        }
        
        if (isset($data['tag'])) {
            $unsubscribe->setTag($data['tag']);
        }
        
        if (isset($data['created_at'])) {
            $unsubscribe->setCreatedAt(new \DateTime($data['created_at']));
        }

        return $unsubscribe;
    }

	/**
	 * Get the event identifier
	 * 
     * @return string The event identifier
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set the event identifier 
     * 
     * @param string $id The event identifier
     */
    private function setId($id = NULL)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * Get the email address unsubscribed
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the email address unsubscribed
     * 
     * @param string $address The email address unsubscribed
     */
    private function setAddress($address = NULL)
    {
        $this->address = $address;
        
        return $this;
    }
    
    /**
     * Get the tag 
     * 
     * Note: * means all unsubscribe address from domain
     * 
     * If NULL the value means * (all for a domain)
     * 
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set the tag 
     * 
     * Note: * means all unsubscribe address from domain
     * 
     * If NULL the value means * (all for a domain)
     * 
     * @param string $tag The tag
     */
    private function setTag($tag = NULL)
    {
        $this->tag = $tag;
        
        return $this;
    }

    /**
     * The creation date for the event
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * The creation date for the event
     * 
     * @param \DateTime $createdAt The creation date for the event
     */
    private function setCreatedAt(\DateTime $createdAt = NULL)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
}
