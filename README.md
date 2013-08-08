Mailgun-PHP
===========
[![Build Status](https://travis-ci.org/travelton/Mailgun-PHP.png?branch=master)](https://travis-ci.org/travelton/Mailgun-PHP)

This is the Mailgun PHP SDK. This SDK contains methods for easily interacting with the Mailgun API. Below are examples to get you started. For additional examples, 
please see our SDK documentation at http://documentation.mailgun.com

Current Release: 0.4-Beta

Installation
------------
To install the SDK, you will need to be using Composer in your project. If you aren't using Composer yet, it's really simple! Here's how to install composer and the Mailgun SDK.

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
Using the SDK should feel simple, if you're already familiar with our API endpoints. If not, no problem... When you're reviewing our documentation, use the provided resource URL when creating the HTTP request.

For example, here's how to use the "Messages" API endpoint:

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Now, compose and send your message.
$mg->post('{$domain}/messages', array('from'	=> 'bob@example.com', 
                           			  'to'		=> 'sally@example.com', 
						   			  'subject'	=> 'The PHP SDK is awesome!', 
						   			  'text'	=> 'It is so simple to send a message.'));
```

For usage examples on each API endpoint, head over to our official documentation pages. 

This SDK includes a [Message Builder](src/Mailgun/Messages/README.md) and [Batch Message](src/Mailgun/Messages/README.md) component.

Support and Feedback
--------------------

Be sure to visit the Mailgun official [documentation website](http://documentation.mailgun.com/) for additional information about our API. 

If you find a bug, please submit the issue in Github directly. [Mailgun-PHP Issues](https://github.com/mailgun/Mailgun-PHP/issues)

As always, if you need additional assistance, drop us a note at [support@mailgun.com](mailto:support@mailgun.com).
