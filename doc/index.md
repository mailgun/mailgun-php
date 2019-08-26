# API documentation

This page will document the API classes and ways to properly use the API. These resources will eventually move to
the official documentation at [https://documentation.mailgun.com](https://documentation.mailgun.com/api_reference.html).

Other relevant documentation pages might be:

* [Attachments](attachments.md)
* [Pagination](pagination.md)
* [Message Builder](/src/Message/README.md)
* [Batch Message](/src/Message/README.md)

## Domain API

#### Get a list of all domains

```php
$mailgun->domains()->index();
```

#### Show a single domains

```php
$mailgun->domains()->show('example.com');
```

#### Verify a domain

```php
$mailgun->domains()->verify('example.com');
```

#### Create a new domain

```php
$mailgun->domains()->create('new.example.com', 'password', 'disable', '*');
```

#### Delete a domain

```php
$mailgun->domains()->delete('example.com');
```

#### Get credentials for a domain

```php
$mailgun->domains()->credentials('example.com');
```

#### Create credentials for a domain

```php
$mailgun->domains()->createCredential('example.com', 'login', 'password');
```

#### Update credentials for a domain

```php
$mailgun->domains()->updateCredential('example.com', 'login', 'password');
```

#### Delete credentials for a domain

```php
$mailgun->domains()->deleteCredential('example.com', 'login');
```

#### Get connection for a domain

```php
$mailgun->domains()->connection('example.com');
```

#### Update connection for a domain

```php
$mailgun->domains()->updateConnection('example.com', true, false);
```

## Event API

#### Get all events for a domain
```php
$mailgun->events()->get('example.com');
```

## Message API

#### Send a message
```php
$parameters = [
    'from'    => 'bob@example.com',
    'to'      => 'sally@example.com',
    'subject' => 'The PHP SDK is awesome!',
    'text'    => 'It is so simple to send a message.'
];
$mailgun->messages()->send('example.com', $parameters);
```
#### Send a message with Mime

Below in an example how to create a Mime message with SwiftMailer.

```php
$message = new Swift_Message('Mail Subject');
$message->setFrom(['from@exemple.com' => 'Example Inc']);
$message->setTo(['user0gmail.com' => 'User 0', 'user1@hotmail.com' => 'User 1']);
// $message->setBcc('admin@example.com'); Do not do this, BCC will be visible for all receipients if you do.
$message->setCc('invoice@example.com');

$messageBody = 'Look at the <b>fancy</b> HTML body.';
$message->setBody($messageBody, 'text/html');

// We need all "tos". Incluce the BCC here.
$to = ['admin@example.com', 'user0gmail.com', 'user1@hotmail.com', 'invoice@example.com']

// Send the message
$mailgun->messages()->sendMime('example.com', $to, $message->toString(), []);
```

#### Show a stored message

If you got an URL to a stored message you may get the details by:

```php
$url = // ...
$mailgun->messages()->show($url);
```

## Route API

#### Show all routes

```php
$mailgun->routes()->index();
```

#### Show a routes

Get a route by its ID

```php
$mailgun->routes()->show(4711);
```
#### Create a route

```php
$expression = "match_recipient('.*@gmail.com')";
$actions = ["forward('alice@example.com')"];
$description = 'Test route';

$mailgun->routes()->create($expression, $actions, $description);
```

#### Update a route

```php
$expression = "match_recipient('.*@gmail.com')";
$actions = ["forward('alice@example.com')"];
$description = 'Test route';

$mailgun->routes()->update(4711, $expression, $actions, $description);
```

#### Delete a route
```php
$mailgun->routes()->delete(4711);
```

## Stats API

#### Get total stats for a domain
```php
$mailgun->stats()->total('example.com');
```

#### Get all stats for a domain
```php
$mailgun->stats()->all('example.com');
```

## Suppression API

The suppression API consists of 3 parts; `Bounce`, `Complaint` and `Unsubscribe`.

### Bounce API
#### Get all bounces
```php
$mailgun->suppressions()->bounces()->index('example.com');
```

#### Show bounces for a specific address
```php
$mailgun->suppressions()->bounces()->show('example.com', 'alice@gmail.com');
```

#### Create a bounce
```php
$mailgun->suppressions()->bounces()->create('example.com', 'alice@gmail.com');
```

#### Delete a bounce
```php
$mailgun->suppressions()->bounces()->delete('example.com', 'alice@gmail.com');
```

#### Delete all bounces
```php
$mailgun->suppressions()->bounces()->deleteAll('example.com');
```

### Complaint API
#### Get all complaints
```php
$mailgun->suppressions()->complaints()->index('example.com');
```

#### Show complaints for a specific address
```php
$mailgun->suppressions()->complaints()->show('example.com', 'alice@gmail.com');
```

#### Create a complaint
```php
$mailgun->suppressions()->complaints()->create('example.com', 'alice@gmail.com');
```

#### Delete a complaint
```php
$mailgun->suppressions()->complaints()->delete('example.com', 'alice@gmail.com');
```

#### Delete all complaints
```php
$mailgun->suppressions()->complaints()->deleteAll('example.com');
```

## Unsubscribe API

#### Get all unsubscriptions
```php
$mailgun->suppressions()->unsubscribes()->index('example.com');
```

#### Show unsubscriptions for a specific address
```php
$mailgun->suppressions()->unsubscribes()->show('example.com', 'alice@gmail.com');
```

#### Create an unsubscription
```php
$mailgun->suppressions()->unsubscribes()->create('example.com', 'alice@gmail.com');
```

#### Delete an unsubscription
```php
$mailgun->suppressions()->unsubscribes()->delete('example.com', 'alice@gmail.com');
```

#### Delete all unsubscriptions
```php
$mailgun->suppressions()->unsubscribes()->deleteAll('example.com');
```

## Tag API

#### Show all tags
```php
$mailgun->tags()->index('example.com');
```

#### Show a single tag
```php
$mailgun->tags()->show('example.com', 'foo');
```

#### Update a tag
```php
$mailgun->tags()->update('example.com', 'foo', 'description');
```

#### Show stats for a tag
```php
$mailgun->tags()->stats('example.com', 'foo');
```

#### Delete a tag
```php
$mailgun->tags()->delete('example.com', 'foo');
```

## Webhook API
#### Verify webhook signature
```php

$timestamp = $_POST['timestamp'];
$token = $_POST['token'];
$signature = $_POST['signature'];

$mailgun = Mailgun::create('my_api_key');
$valid = $mailgun->webhooks()->verifyWebhookSignature($timestamp, $token, $signature);

if (!$valid) {
    // Create a 403 response

    exit();
}

// The signature is valid
```

#### Show all webhooks
```php
$mailgun->webhooks()->index('example.com');
```

#### Show a single webhooks
```php
$mailgun->webhooks()->show('example.com', 'accept');
```

#### Create a webhooks
```php
$mailgun->webhooks()->create('example.com', 'accept', 'https://www.exmple.com/webhook');
```

#### Update a webhooks
```php
$mailgun->webhooks()->update('example.com', 4711, 'https://www.exmple.com/webhook');
```

#### Delete a webhooks
```php
$mailgun->webhooks()->delete('example.com', 4711);
```
