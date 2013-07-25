Mailgun-PHP-SDK
===========
This is the Mailgun PHP SDK. This SDK contains methods for easily interacting with the Mailgun API. Below are examples for utilizing the SDK!

Installation
-----
To install the SDK, you need to be using Composer in your project. If you aren't using Composer yet, it's really simple! Here's how to install composer and the Mailgun SDK.

```PHP
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Mailgun as a dependency
php composer.phar require mailgun/mailgun-php-sdk:~1.0
``` 
Next, require Composer's autoloader to automatically load the Mailgun SDK:
```PHP
require 'vendor/autoload.php';
```

Usage
-----
Using the SDK is rather simple, if you're already familiar with our API. If not, no problem... Just know that the classes follow the API endpoints. So when you're reviewing our documentation, the endpoints are expressed as a class. 

Here's an example for sending a message: 

```php
# First, instantiate the client with your API credentials and domain. 
$client = new MailgunClient("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org");

# Next, create a message object.
$message = $client->Messages();

# Now, compose your message.
$message->setMessage(array('from' => 'me@samples.mailgun.org', 
                           'to' => 'php-sdk@mailgun.net', 
                           'subject' => 'The PHP SDK is awesome!', 
                           'text' => 'It is so simple to send a message.'));

# Finally, send the message.
$message->sendMessage();
```
