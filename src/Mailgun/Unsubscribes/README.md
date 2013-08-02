Mailgun - Unsubscribes
======================

This is the Mailgun PHP *Unsubscribes* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Unsubscribes" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Unsubscribes object on the Unsubscribes API endpoint.
$unsubscribe = $mgClient->Unsubscribes();

# Next, unsubscribe the address from the weekly-emails tag.
$unsubscribe->addAddress("removeme@samples.mailgun.org", "weekly-emails");
```

Available Functions
-------------------

`addAddress(string $unsubAddress, string $unsubTag)`  

`deleteAddress(string $unsubAddress)`  

`getUnsubscribe(string $unsubAddress)`  

`getUnsubscribes(int $limit, int $skip)`  


More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-unsubscribes.html) for more information.