Mailgun-PHP
===========
[![Build Status](https://travis-ci.org/travelton/Mailgun-PHP.png?branch=master)](https://travis-ci.org/travelton/Mailgun-PHP)

This is the Mailgun PHP SDK. This SDK contains methods for easily interacting with the Mailgun API. Below are examples to get you started. For additional examples, 
please see our SDK documentation at http://documentation.mailgun.com

Installation
-----
To install the SDK, you will need to be using Composer in your project. If you aren't using Composer yet, it's really simple! Here's how to install composer and the Mailgun SDK.

```PHP
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Mailgun as a dependency
php composer.phar require mailgun/mailgun-php-sdk:~0.1
``` 
Next, require Composer's autoloader, in your application, to automatically load the Mailgun SDK in your project:
```PHP
require 'vendor/autoload.php';
```
For shared hosts with SSH access, you might need to run this instead (contact your shared host for assistance): 
```
php -d detect_unicode=Off -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
```

Usage
-----
Using the SDK should feel simple, if you're already familiar with our API endpoints. If not, no problem... When you're reviewing our documentation, the endpoints are expressed as a class in the SDK to make things easier. 

For example, here's how to use the "Messages" API endpoint:

```php
# First, instantiate the client with your API credentials and domain. 
$mgClient = new MailgunClient("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org");

# Next, instantiate a Message object on the messages API endpoint.
$message = $mgClient->Messages();

# Now, compose your message.
$message->setMessage(array('from' => 'me@samples.mailgun.org', 
                           'to' => 'php-sdk@mailgun.net', 
                           'subject' => 'The PHP SDK is awesome!', 
                           'text' => 'It is so simple to send a message.'));

# Finally, send the message.
$message->sendMessage();
```

Advanced Usage
--------------
You've sent your first message, awesome! Let's move on to more advanced use cases. 

#### Message Builder
Message Builder makes creating your messages really intuitive. If you despise arrays, or your workflow is better off defining each part of the message separately, use Message Builder!

```php
# First, instantiate the client with your API credentials and domain. 
$mgClient = new MailgunClient("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org");

# Next, instantiate a Message Builder object on the Messages API endpoint.
$message = $mgClient->Messages()->MessageBuilder();

# Define the from address.
$message->setFromAddress("me@samples.mailgun.org", array("first"=>"PHP", "last" => "SDK"));
# Define a to recipient.
$message->addToRecipient("john.doe@samples.mailgun.org", array("first" => "John", "last" => "Doe"));
# Define a cc recipient.
$message->addCcRecipient("sally.doe@samples.mailgun.org", array("first" => "Sally", "last" => "Doe"));
# Define the subject. 
$message->setSubject("A message from the PHP SDK using Message Builder!");
# Define the body of the message.
$message->setTextBody("This is the text body of the message!");

# Other Optional Parameters.
$message->addCampaignId("My-Awesome-Campaign");
$message->addCustomHeader("Customer-Id", "12345");
$message->addAttachment("@/tron.jpg");
$message->setDeliveryTime("tomorrow 8:00AM", "PST");
$message->setClickTracking(true);

# Finally, send the message.
$message->sendMessage();

```

#### Batch Sending
Batch sending allows you to submit up to 1,000 messages per API call. This is the best way to send a large amount of messages as quickly as possible. In the example below, we'll use the Message Builder object to create a message. 

_Note: While Message Builder is the preferred method, you can also run "setMessage()" (see above example) to pass a fully formed array of data._

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
