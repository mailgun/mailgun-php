Mailgun - Messages
====================

This is the Mailgun PHP *Message* utilities. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

There are two utilities included, Message Builder and Batch Message. 

Message Builder: Allows you to build a message object by calling methods for each MIME attribute. 
Batch Message: Inherits Message Builder and allows you to iterate through recipients from a list. Messages will fire after the 1,000th recipient has been added. 

Usage - Message Builder
-----------------------
Here's how to use Message Builder to build your Message. 

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Next, instantiate a Message Builder object from the SDK.
$messageBldr = $mg->MessageBuilder();

# Define the from address.
$messageBldr->setFromAddress("me@example.com", array("first"=>"PHP", "last" => "SDK"));
# Define a to recipient.
$messageBldr->addToRecipient("john.doe@example.com", array("first" => "John", "last" => "Doe"));
# Define a cc recipient.
$messageBldr->addCcRecipient("sally.doe@example.com", array("first" => "Sally", "last" => "Doe"));
# Define the subject. 
$messageBldr->setSubject("A message from the PHP SDK using Message Builder!");
# Define the body of the message.
$messageBldr->setTextBody("This is the text body of the message!");

# Other Optional Parameters.
$messageBldr->addCampaignId("My-Awesome-Campaign");
$messageBldr->addCustomHeader("Customer-Id", "12345");
$messageBldr->addAttachment("@/tron.jpg");
$messageBldr->setDeliveryTime("tomorrow 8:00AM", "PST");
$messageBldr->setClickTracking(true);

# Finally, send the message.
$mg->post('{$domain}/messages', $messageBldr->getMessage());
```

Available Functions
-----------------------------------------------------

`addToRecipient(string $address, array $attributes)` 

`addCcRecipient(string $address, array $attributes)`  

`addBccRecipient(string $address, array $attributes)`  

`setFromAddress(string $address, array $attributes)`  

`setSubject(string $subject)`  

`setTextBody(string $textBody)`  

`setHtmlBody(string $htmlBody)`  

`addAttachment(string $attachmentPath)`  

`addInlineImage(string $inlineImagePath)`  

`setTestMode(bool $testMode)`  

`addCampaignId(string $campaignId)`  

`setDkim(bool $enabled)`  

`setOpenTracking($enabled)`  

`setClickTracking($enabled)`  

`setDeliveryTime(string $timeDate, string $timeZone)`  

`addCustomOption(string $optionName, string $data)`  

`addCustomParameter(string $parameterName, string $data)`

`setMessage(array $message, array $files)`

`getMessage()`  

`getFiles()`  


Usage - Batch Message
---------------------
Here's how to use Batch Message to easily handle batch sending jobs. 

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Next, instantiate a Message Builder object from the SDK, pass in your sending domain.
$batchMsg = $mg->BatchMessage($domain);

# Define the from address.
$batchMsg->setFromAddress("me@samples.mailgun.org", array("first"=>"PHP", "last" => "SDK"));
# Define the subject. 
$batchMsg->setSubject("A Batch Message from the PHP SDK!");
# Define the body of the message.
$batchMsg->setTextBody("This is the text body of the message!");

# Next, let's add a few recipients to the batch job.
$batchMsg->addToRecipient("john.doe@samples.mailgun.org", array("first" => "John", "last" => "Doe"));
$batchMsg->addToRecipient("sally.doe@samples.mailgun.org", array("first" => "Sally", "last" => "Doe"));
$batchMsg->addToRecipient("mike.jones@samples.mailgun.org", array("first" => "Mike", "last" => "Jones"));
...
// After 1,000 recipeints, Batch Message will automatically post your message to the messages endpoint. 

// Call finalize() to send any remaining recipients still in the buffer.
$batchMsg->finalize();

```

Available Functions (Inherits all Batch Message and Messages Functions)
-----------------------------------------------------------------------

`addToRecipient(string $address, string $attributes)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-sending.html) for more information.