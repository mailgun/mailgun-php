# Mailgun PHP client

This is the Mailgun PHP SDK. This SDK contains methods for easily interacting
with the Mailgun API. Below are examples to get you started. For additional
examples, please see our official documentation at http://documentation.mailgun.com

[![Latest Version](https://img.shields.io/github/release/mailgun/mailgun-php.svg?style=flat-square)](https://github.com/mailgun/mailgun-php/releases)
[![Build Status](https://img.shields.io/travis/mailgun/mailgun-php/master.svg?style=flat-square)](https://travis-ci.org/mailgun/mailgun-php)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/mailgun/mailgun-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailgun/mailgun-php)
[![Quality Score](https://img.shields.io/scrutinizer/g/mailgun/mailgun-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailgun/mailgun-php)
[![Total Downloads](https://img.shields.io/packagist/dt/mailgun/mailgun-php.svg?style=flat-square)](https://packagist.org/packages/mailgun/mailgun-php)
[![Join the chat at https://gitter.im/mailgun/mailgun-php](https://badges.gitter.im/mailgun/mailgun-php.svg)](https://gitter.im/mailgun/mailgun-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Installation

To install the SDK, you will need to be using [Composer](http://getcomposer.org/) 
in your project. 
If you aren't using Composer yet, it's really simple! Here's how to install 
composer:

```bash
curl -sS https://getcomposer.org/installer | php
```

The Mailgun API Client is not hard coupled to Guzzle, Buzz or any other library that sends
HTTP messages. Instead, it uses the [PSR-18](https://www.php-fig.org/psr/psr-18/) client abstraction.
This will give you the flexibility to choose what
[PSR-7 implementation and HTTP client](https://packagist.org/providers/php-http/client-implementation)
you want to use. 

If you just want to get started quickly you should run the following command: 

```bash
composer require mailgun/mailgun-php kriswallsmith/buzz nyholm/psr7
```

## Usage

You should always use Composer autoloader in your application to automatically load
your dependencies. All the examples below assume you've already included this in your
file:

```php
require 'vendor/autoload.php';
use Mailgun\Mailgun;
```

Here's how to send a message using the SDK:

```php
// First, instantiate the SDK with your API credentials
$mg = Mailgun::create('key-example'); // For US servers
$mg = Mailgun::create('key-example', 'https://api.eu.mailgun.net'); // For EU servers

// Now, compose and send your message.
// $mg->messages()->send($domain, $params);
$mg->messages()->send('example.com', [
  'from'    => 'bob@example.com',
  'to'      => 'sally@example.com',
  'subject' => 'The PHP SDK is awesome!',
  'text'    => 'It is so simple to send a message.'
]);
```

Attention: `$domain` must match to the domain you have configured on [app.mailgun.com](https://app.mailgun.com/app/domains).

### All usage examples

You will find more detailed documentation at [/doc](doc/index.md) and on 
[https://documentation.mailgun.com](https://documentation.mailgun.com/en/latest/api_reference.html).

### Response

The result of an API call is, by default, a domain object. This will make it easy
to understand the response without reading the documentation. One can just read the
doc blocks on the response classes. This provides an excellent IDE integration.
 
```php
$mg = Mailgun::create('key-example');
$dns = $mg->domains()->show('example.com')->getInboundDNSRecords();

foreach ($dns as $record) {
  echo $record->getType();
}
```

If you'd rather work with an array than an object you can inject the `ArrayHydrator`
to the Mailgun class. 

```php
use Mailgun\Hydrator\ArrayHydrator;

$configurator = new HttpClientConfigurator();
$configurator->setApiKey('key-example');

$mg = new Mailgun($configurator, new ArrayHydrator());
$data = $mg->domains()->show('example.com');

foreach ($data['receiving_dns_records'] as $record) {
  echo isset($record['record_type']) ? $record['record_type'] : null;
}
```

You can also use the `NoopHydrator` to get a PSR7 Response returned from 
the API calls. 

**Warning: When using `NoopHydrator` there will be no exceptions on a non-200 response.**

### Debugging

Debugging the PHP SDK can be helpful when things aren't working quite right. 
To debug the SDK, here are some suggestions: 

Set the endpoint to Mailgun's Postbin. A Postbin is a web service that allows you to
post data, which then you can display it through a browser. Using Postbin is an easy way
to quickly determine what data you're transmitting to Mailgun's API.

**Step 1 - Create a new Postbin.**  
Go to http://bin.mailgun.net. The Postbin will generate a special URL. Save that URL. 

**Step 2 - Instantiate the Mailgun client using Postbin.**  

*Tip: The bin id will be the URL part after bin.mailgun.net. It will be random generated letters and numbers. 
For example, the bin id in this URL (http://bin.mailgun.net/aecf68de) is `aecf68de`.*

```php
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Hydrator\NoopHydrator;

$configurator = new HttpClientConfigurator();
$configurator->setEndpoint('http://bin.mailgun.net/aecf68de');
$configurator->setApiKey('key-example');
$configurator->setDebug(true);

$mg = new Mailgun($configurator, new NoopHydrator());

# Now, compose and send your message.
$mg->messages()->send('example.com', [
  'from'    => 'bob@example.com', 
  'to'      => 'sally@example.com', 
  'subject' => 'The PHP SDK is awesome!', 
  'text'    => 'It is so simple to send a message.'
]);
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

* [tehplague/swiftmailer-mailgun-bundle](https://github.com/tehplague/swiftmailer-mailgun-bundle) for Symfony
* [katanyoo/yii2-mailgun-mailer](https://github.com/katanyoo/yii2-mailgun-mailer) for Yii2
* [narendravaghela/cakephp-mailgun](https://github.com/narendravaghela/cakephp-mailgun) for CakePHP
* [drupal/mailgun](https://www.drupal.org/project/mailgun) for Drupal

## Contribute

This SDK is an Open Source under the MIT license. It is, thus, maintained by collaborators and contributors.

Feel free to contribute in any way. As an example you may: 
* Trying out the `dev-master` code
* Create issues if you find problems
* Reply to other people's issues
* Review PRs

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

As always, if you need additional assistance, drop us a note through your account at
[https://app.mailgun.com/app/support/list](https://app.mailgun.com/app/support/list).
