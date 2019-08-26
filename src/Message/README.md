Mailgun - Messages
==================

This is the Mailgun PHP *Message* utilities. 

The below assumes you've already installed the Mailgun PHP SDK in to your 
project. If not, go back to the master README for instructions.

There are two utilities included, `MessageBuilder` and `BatchMessage`. 

* `MessageBuilder`: Allows you to build a message object by calling methods for 
each MIME attribute. 
* `BatchMessage`: Extends `MessageBuilder` and allows you to iterate through 
recipients from a list. Messages will fire after the 1,000th recipient has been 
added. 

Usage - Message Builder
-----------------------
Here's how to use Message Builder to build your Message. 

```php
# Next, instantiate a Message Builder object from the SDK.
$builder = new MessageBuilder();

# Define the from address.
$builder->setFromAddress("me@example.com", array("first"=>"PHP", "last" => "SDK"));
# Define a to recipient.
$builder->addToRecipient("john.doe@example.com", array("first" => "John", "last" => "Doe"));
# Define a cc recipient.
$builder->addCcRecipient("sally.doe@example.com", array("full_name" => "Sally Doe"));
# Define the subject. 
$builder->setSubject("A message from the PHP SDK using Message Builder!");
# Define the body of the message.
$builder->setTextBody("This is the text body of the message!");

# Other Optional Parameters.
$builder->addCampaignId("My-Awesome-Campaign");
$builder->addCustomHeader("Customer-Id", "12345");
$builder->addAttachment("@/tron.jpg");
$builder->setDeliveryTime("tomorrow 8:00AM", "PST");
$builder->setClickTracking(true);

# Finally, send the message.
$mg = Mailgun::create('key-example');
$domain = ;
$mg->messages()->send("example.com", $builder->getMessage());
```

Usage - Batch Message
---------------------
Here's how to use Batch Message to easily handle batch sending jobs. 

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");

# Next, instantiate a Message Builder object from the SDK, pass in your sending domain.
$batchMessage = $mg->messages()->getBatchMessage("example.com");

# Define the from address.
$batchMessage->setFromAddress("me@example.com", array("first"=>"PHP", "last" => "SDK"));
# Define the subject. 
$batchMessage->setSubject("A Batch Message from the PHP SDK!");
# Define the body of the message.
$batchMessage->setTextBody("This is the text body of the message!");

# Next, let's add a few recipients to the batch job.
$batchMessage->addToRecipient("john.doe@example.com", array("first" => "John", "last" => "Doe"));
$batchMessage->addToRecipient("sally.doe@example.com", array("first" => "Sally", "last" => "Doe"));
$batchMessage->addToRecipient("mike.jones@example.com", array("first" => "Mike", "last" => "Jones"));
...
// After 1,000 recipients, Batch Message will automatically post your message to the messages endpoint. 

// Call finalize() to send any remaining recipients still in the buffer.
$batchMessage->finalize();

$messageIds = $batchMessage->getMessageIds();

```

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-sending.html) 
for more information.
