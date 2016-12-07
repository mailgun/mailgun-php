# Mailgun PHP client

This is the Mailgun PHP SDK. This SDK contains methods for easily interacting 
with the Mailgun API. 
Below are examples to get you started. For additional examples, please see our 
official documentation 
at http://documentation.mailgun.com

[![Latest Version](https://img.shields.io/github/release/mailgun/mailgun-php.svg?style=flat-square)](https://github.com/mailgun/mailgun-php/releases)
[![Build Status](https://img.shields.io/travis/mailgun/mailgun-php.svg?style=flat-square)](https://travis-ci.org/mailgun/mailgun-php)
[![StyleCI](https://styleci.io/repos/11654443/shield?branch=master)](https://styleci.io/repos/11654443)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/mailgun/mailgun-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailgun/mailgun-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/mailgun/mailgun-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailgun/mailgun-php)
[![Total Downloads](https://img.shields.io/packagist/dt/mailgun/mailgun-php.svg?style=flat-square)](https://packagist.org/packages/mailgun/mailgun-php)

**This is the documentation for dev-master. You find documentation for the latest stable 
release [here](https://github.com/mailgun/mailgun-php/tree/v2.1.2).**

## Installation

To install the SDK, you will need to be using [Composer](http://getcomposer.org/) 
in your project. 
If you aren't using Composer yet, it's really simple! Here's how to install 
composer:

```bash
curl -sS https://getcomposer.org/installer | php
```

The Mailgun api client is not hard coupled to Guzzle or any other library that sends HTTP messages. It uses an abstraction 
called HTTPlug. This will give you the flexibilty to choose what PSR-7 implementation and HTTP client to use. 

If you just want to get started quickly you should run the following command: 

```bash
php composer.phar require mailgun/mailgun-php php-http/curl-client guzzlehttp/psr7
```
**For shared hosts without SSH access, check out our [Shared Host Instructions](SharedHostInstall.md).**

### Why requiring so many packages?

Mailgun has a dependency on the virtual package
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) which requires to you install **an** adapter, but we do not care which one. That is an implementation detail in your application. We also need **a** PSR-7 implementation and **a** message factory. 

You do not have to use the `php-http/curl-client` if you do not want to. You may use the `php-http/guzzle6-adapter`. Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](http://docs.php-http.org/en/latest/httplug/users.html).

## Usage

You should always use Composer's autoloader in your application to automatically load the your dependencies. All examples below assumes you've already included this in your file:

```php
require 'vendor/autoload.php';
use Mailgun\Mailgun;
```

Here's how to send a message using the SDK:

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun("key-example");
$domain = "example.com";

# Now, compose and send your message.
$mg->sendMessage($domain, array('from'    => 'bob@example.com', 
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
$mg->get("$domain/log", array('limit' => 25, 
                              'skip'  => 0));
```

### Response

The results of a API call is, by default, a domain object. This will make it easy
to understand the response without reading the documentation. One can just read the
doc blocks on the response classes. This provide an excellet IDE integration.
 
```php
$mg = new Mailgun("key-example");
$dns = $mg->domains()->show('example.com')->getInboundDNSRecords();

foreach ($dns as $record) {
  echo $record->getType();
}
```

If you rather be working with array then object you can inject the `ArrayDeserializer`
to the Mailgun class. 

```php
use Mailgun\Deserializer\ArrayDeserializer;

$mg = new Mailgun("key-example", null, null, new ArrayDeserializer());
$data = $mg->domains()->show('example.com');

foreach ($data['receiving_dns_records'] as $record) {
  echo isset($record['record_type']) ? $record['record_type'] : null;
}
```

You could also use the `PSR7Deserializer` to get a raw PSR7 Response returned from 
the API calls. 

### Debugging

Debugging the PHP SDK can be really helpful when things aren't working quite right. 
To debug the SDK, here are some suggestions: 

Set the endpoint to Mailgun's Postbin. A Postbin is a web service that allows you to 
post data, which is then displayed through a browser. This allows you to quickly determine
what is actually being transmitted to Mailgun's API. 

**Step 1 - Create a new Postbin.**  
Go to http://bin.mailgun.net. The Postbin will generate a special URL. Save that URL. 

**Step 2 - Instantiate the Mailgun client using Postbin.**  

*Tip: The bin id will be the URL part after bin.mailgun.net. It will be random generated letters and numbers. For example, the bin id in this URL, http://bin.mailgun.net/aecf68de, is "aecf68de".*

```php
# First, instantiate the SDK with your API credentials and define your domain. 
$mg = new Mailgun('key-example', null, 'bin.mailgun.net');
$mg->setApiVersion('aecf68de');
$mg->setSslEnabled(false);
$domain = 'example.com';

# Now, compose and send your message.
$mg->sendMessage($domain, array('from'    => 'bob@example.com', 
                                'to'      => 'sally@example.com', 
                                'subject' => 'The PHP SDK is awesome!', 
                                'text'    => 'It is so simple to send a message.'));
```
### Additional Info

For usage examples on each API endpoint, head over to our official documentation 
pages. 

This SDK includes a [Message Builder](src/Mailgun/Messages/README.md), 
[Batch Message](src/Mailgun/Messages/README.md) and [Opt-In Handler](src/Mailgun/Lists/README.md) component.

Message Builder allows you to quickly create the array of parameters, required 
to send a message, by calling a methods for each parameter.
Batch Message is an extension of Message Builder, and allows you to easily send 
a batch message job within a few seconds. The complexity of 
batch messaging is eliminated! 

## Framework integration

If you are using a framework you might consider these composer packages to make the framework integration easier. 

* [tehplague/swiftmailer-mailgun-bundle](https://github.com/tehplague/swiftmailer-mailgun-bundle) for Symfony2
* [Bogardo/Mailgun](https://github.com/Bogardo/Mailgun) for Laravel 4
* [katanyoo/yii2-mailgun-mailer](https://github.com/katanyoo/yii2-mailgun-mailer) for Yii2

## Contribute

We are currently building a new object oriented API client. Feel free to contribute in any way. As an example you may: 
* Trying out dev-master the code
* Create issues if you find problems
* Reply to other people's issues
* Review PRs
* Write PR. You find our current milestone [here](https://github.com/mailgun/mailgun-php/milestone/1) 

### Running the test code

If you want to run the tests you should run the following commands: 

```terminal
git clone git@github.com:mailgun/mailgun-php.git
cd mailgun-php
composer update
composer test
```

## Support and Feedback

Be sure to visit the Mailgun official 
[documentation website](http://documentation.mailgun.com/) for additional 
information about our API. 

If you find a bug, please submit the issue in Github directly. 
[Mailgun-PHP Issues](https://github.com/mailgun/mailgun-php/issues)

As always, if you need additional assistance, drop us a note through your Control Panel at
[https://mailgun.com/cp/support](https://mailgun.com/cp/support).

