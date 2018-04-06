<?php
namespace Mailgun\Model\MailingList;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class Member
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var array
     */
    protected $vars;

    /**
     * @var boolean
     */
    protected $subscribed;


    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['address']) ? $data['address'] : null,
            isset($data['vars']) ? $data['vars'] : [],
            isset($data['subscribed']) ? !!$data['subscribed'] : null
        );
    }

    public function __construct($name, $address, $vars = [], $subscribed = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->vars = $vars;
        $this->subscribed = $subscribed;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {
        return $this->subscribed;
    }
}