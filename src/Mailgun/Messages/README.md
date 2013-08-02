Mailgun - Messages
====================

This is the Mailgun PHP *Messages* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage - Messages
----------------
Here's how to use the "Messages" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Message object on the Messages API endpoint.
$message = $mgClient->Messages();

# Next, set the message content.
$message->setMessage(array('from' => 'me@samples.mailgun.org', 
                           'to' => 'php-sdk@mailgun.net', 
                           'subject' => 'The PHP SDK is awesome!', 
                           'text' => 'It is so simple to send a message.'));

# Finally, send the message.
$message->sendMessage();
```

Available Functions
-------------------

`sendMessage(array $message, array $files)`  

`setMessage(array $message, array $files)`  

`sendMessage()`  

Usage - Message Builder
---------------------
Here's how to use the "Messages" API endpoint with Message Builder:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Message Builder object on the Messages API endpoint.
$messageBldr = $mgClient->Messages()->MessageBuilder();

# Define the from address.
$messageBldr->setFromAddress("me@samples.mailgun.org", array("first"=>"PHP", "last" => "SDK"));
# Define a to recipient.
$messageBldr->addToRecipient("john.doe@samples.mailgun.org", array("first" => "John", "last" => "Doe"));
# Define a cc recipient.
$messageBldr->addCcRecipient("sally.doe@samples.mailgun.org", array("first" => "Sally", "last" => "Doe"));
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
$messageBldr->sendMessage();
```

Available Functions (Inherits all Messages Functions)
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

`getMessage()`  

`getFiles()`  


Usage - Batch Sending
---------------------
Here's how to use the "Messages" API endpoint with Batch Sending:

```php
# First, instantiate the client with your API credentials and domain. 
$mgClient = new MailgunClient("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org");

# Next, instantiate a Batch Message object on the Messages API endpoint. 
$batchMessage = $mgClient->Messages()->BatchMessage();

# Define the from address.
$batchMessage->setFromAddress("me@samples.mailgun.org", array("first"=>"PHP", "last" => "SDK"));
# Define the subject. 
$batchMessage->setSubject("A Batch Message from the PHP SDK!");
# Define the body of the message.
$batchMessage->setTextBody("This is the text body of the message!");

# Next, let's add a few recipients to the batch job.
$batchMessage->addBatchRecipient("john.doe@samples.mailgun.org", array("first" => "John", "last" => "Doe"));
$batchMessage->addBatchRecipient("sally.doe@samples.mailgun.org", array("first" => "Sally", "last" => "Doe"));
$batchMessage->addBatchRecipient("mike.jones@samples.mailgun.org", array("first" => "Mike", "last" => "Jones"));

# Finally, submit the batch job.
$batchMessage->sendMessage();
```

Available Functions (Inherits all Batch Message and Messages Functions)
-----------------------------------------------------------------------

`addBatchRecipient(string $address, string $attributes)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-sending.html) for more information.