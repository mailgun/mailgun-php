Mailgun-PHP
===========
[![Build Status](https://travis-ci.org/travelton/Mailgun-PHP.png?branch=master)](https://travis-ci.org/travelton/Mailgun-PHP)

This is the Mailgun PHP SDK. This SDK contains methods for easily interacting with the Mailgun API. 
Below are examples to get you started. For additional examples, please see our official documentation 
at http://documentation.mailgun.com

Current Release: 0.4-Beta

Installation
------------
To install the SDK, you will need to be using [Composer](http://getcomposer.org/) in your project. 
If you aren't using Composer yet, it's really simple! Here's how to install composer and the Mailgun SDK.

```PHP
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Mailgun as a dependency
php composer.phar require mailgun/mailgun-php-sdk:~0.1
``` 

For shared hosts with SSH access, you might need to run this instead (contact your shared host for assistance): 
```
php -d detect_unicode=Off -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
```

Next, require Composer's autoloader, in your application, to automatically load the Mailgun SDK in your project:
```PHP
require 'vendor/autoload.php';
use Mailgun\Mailgun;
```

Usage
-----
Here's how to send a message using the SDK:

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Now, compose and send your message.
$mg->post('{$domain}/messages', array('from'    => 'bob@example.com', 
                                      'to'      => 'sally@example.com', 
                                      'subject' => 'The PHP SDK is awesome!', 
                                      'text'    => 'It is so simple to send a message.'));
```

Or obtain the last 25 log items: 
```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Now, issue a GET against the Logs endpoint.
$mg->get('{$domain}/log', array('limit' => 'bob@example.com', 
                                'skip'  => 'sally@example.com');
```

For usage examples on each API endpoint, head over to our official documentation pages. 

This SDK includes a [Message Builder](src/Mailgun/Messages/README.md) and [Batch Message](src/Mailgun/Messages/README.md) component.

Message Builder allows you to quickly create the array of parameters, required to send a message, by calling a methods for each parameter.
Batch Message is an extension of Message Builder, and allows you to easily send a batch message job within a few seconds. The complexity of 
batch messaging is eliminated! 

Support and Feedback
--------------------

Be sure to visit the Mailgun official [documentation website](http://documentation.mailgun.com/) for additional information about our API. 

If you find a bug, please submit the issue in Github directly. [Mailgun-PHP Issues](https://github.com/mailgun/Mailgun-PHP/issues)

As always, if you need additional assistance, drop us a note at [support@mailgun.com](mailto:support@mailgun.com).
