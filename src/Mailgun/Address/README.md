Mailgun - Address
===================

This is the Mailgun PHP *Email Validation* endpoint. Given an arbitrary address, we will validate the address based on: Syntax checks (RFC defined grammar), DNS validation, Spell checks, Email Service Provider (ESP) specific local-part grammar (if available).

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Address" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate an Address object on the Address API endpoint.
$address = $mgClient->Address();

# Now, validate the address and store the result in $result.
$result = $address->validateAddress("me@samples.mailgun.org");
```

Available Functions
-------------------

`validateAddress(string $address);`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-email-validation.html) for more information.