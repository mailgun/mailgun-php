Mailgun - Bounces
===================

This is the Mailgun PHP *Bounces* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Bounces" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Bounces object on the Bounces API endpoint.
$bounces = $mgClient->Bounces();

# Finally, add an address to the Bounces Table.
$result = $bounces->addAddress("bounce@samples.mailgun.org", 550, "Server not accepting messages for mailbox.");

```

Available Functions
-------------------

`addAddress(string $bounceAddress, int $bounceCode, string $bounceError);`  

`deleteAddress(string $bounceAddress);`  

`getBounce(string $bounceAddress);`  

`getBounces(int $limit, int $skip);`  


More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-bounces.html) for more information.