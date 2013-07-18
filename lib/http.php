<?
namespace Mailgun;
	
require 'vendor/autoload.php';

use Guzzle\Http\Client as Guzzler;

class Client{

	protected $api_key;
	protected $domain;
	protected $client;
		
	public function __construct($api_key, $domain){
		$this->api_key = $api_key;
		$this->domain = $domain;
		$this->client = new Guzzler("https://api.mailgun.net/v2");
		$this->client->setDefaultOption('auth', array ('api', $this->api_key));	
	}
}

?>