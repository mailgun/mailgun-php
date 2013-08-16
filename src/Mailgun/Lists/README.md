Mailgun - Lists
====================

This is the Mailgun PHP *Lists* utilities. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

There is currently one utility provided.

OptInHandler: Provides methods for authenticating an OptInRequest. 

The typical flow for using this utility would be as follow: 
(Recipient Requests Subscribe) -> [Generate Opt In Link] -> [Email Recipient Opt In Link]  
(Recipient Clicks Opt In Link) -> [Validate Opt In Link] -> [Subscribe User] -> [Send final confirmation]  

The above flow is modeled below.

Usage - Opt-In Handler (Recipient Requests Subscribe)
-----------------------------------------------------
Here's how to use Opt-In Handler to validate Opt-In requests. 

```php
# First, instantiate the SDK with your API credentials and domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Next, instantiate an OptInHandler object from the SDK.
$optInHandler = $mg->OptInHandler();

# Next, define necessary variables and generate a hash.
$mailingList = 'youlist@example.com';
$secretPassphrase = 'a_secret_passphrase';
$recipientAddress = 'recipient@example.com'
$generatedHash = $optInHandler->generateHash($mailingList, $secretPassphrase, $recipientAddress);

# Now, let's send a confirmation to the recipient with our link.
$mg->sendMessage($domain, array('from'    => 'bob@example.com', 
                                'to'      => 'sally@example.com', 
                                'subject' => 'Please Confirm!', 
                                'html'    => '<html><body>Hello,<br><br>You have requested to be subscribed 
                                			  to the mailing list {$mailingList}. Please <a 
                                			  href="http://yourdomain.com/subscribe.php?hash=$generatedHash">confirm
                                			  </a> your subscription.<br><br>Thank you!</body></html>'));
                                			  
# Finally, let's add the subscriber to a Mailing List, as unsubscribed, so we can track non-conversions.
$mg->post('{$domain}/lists/{$mailingList}', array('address'    => $recipientAddress, 
                                				  'subscribed' => 'no',
                                				  'upsert'     => 'yes');
```

Usage - Opt-In Handler (Recipient Clicks Opt In Link)
-----------------------------------------------------
Here's how to use Opt-In Handler to validate an Opt-In Hash. 

```php
# First, instantiate the SDK with your API credentials and domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Next, instantiate an OptInHandler object from the SDK.
$optInHandler = $mg->OptInHandler();

# Next, grab the hash.
$inboundHash = $_GET['hash'];
$secretPassphrase = 'a_secret_passphrase';

# Now, validate the captured hash.
$hashValidation = $optInHandler->validateHash($secretPassphrase, $inboundHash);

# Lastly, check to see if we have results, parse, subscribe, and send confirmation.
if($hashValidation){
	$validatedList = $hashValidation['mailingList'];
	$validatedRecipient = $hashValidation['recipientAddress'];
	
	$mg->put('{$domain}/lists/{$validatedList}/{$validatedRecipient}', 
						array('address'    => $recipientAddress, 
                              'subscribed' => 'yes');
    
    $mg->sendMessage($domain, array('from'    => 'bob@example.com', 
                                    'to'      => 'sally@example.com', 
                                    'subject' => 'Please Confirm!', 
                                    'html'    => '<html><body>Hello,<br><br>We\'ve successfully subscribed 
                                	              you to the list, {$mailingList}!<br><br>Thank you!
                                	              </body></html>'));
}
```

A few notes:  
1. 'a_secret_passphrase' can be anything. It's used as the *key* in hashing, since your email address will vary.  
2. validateHash() will return an array containing the recipient address and list address.  
3. You should *always* send an email confirmation before and after the subscription request.  
4. WARNING: On $_GET['hash'], you need to sanitize this value to prevent malicious attempts to inject code.  

Available Functions
-----------------------------------------------------

`string generateHash(string $mailingList, string $secretAppId, string $recipientAddress)` 

`array validateHash(string $secretAppId, string $uniqueHash)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-sending.html) for more information.
