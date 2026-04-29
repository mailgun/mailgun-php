# Mailgun PHP SDK

The official Mailgun PHP SDK — a clean, PSR-18 HTTP client wrapper around the [Mailgun API](https://documentation.mailgun.com/docs/mailgun/api-reference).

[![Latest Version](https://img.shields.io/github/release/mailgun/mailgun-php.svg?style=flat-square)](https://github.com/mailgun/mailgun-php/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/mailgun/mailgun-php.svg?style=flat-square)](https://packagist.org/packages/mailgun/mailgun-php)
[![License](https://img.shields.io/github/license/mailgun/mailgun-php?style=flat-square)](LICENSE)
[![Join the chat at https://gitter.im/mailgun/mailgun-php](https://badges.gitter.im/mailgun/mailgun-php.svg)](https://gitter.im/mailgun/mailgun-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Sending Email](#sending-email)
- [IP Management](#ip-management)
- [Dynamic IP Pools](#dynamic-ip-pools-dipp)
- [Analytics](#analytics)
- [Subaccounts](#subaccounts)
- [Response Handling](#response-handling)
- [Debugging](#debugging)
- [Framework Integration](#framework-integration)
- [Contributing](#contributing)

---

## Requirements

- PHP **7.4** or higher
- A PSR-18 HTTP client (e.g. `symfony/http-client`, `guzzlehttp/guzzle`)
- A PSR-7 / PSR-17 implementation (e.g. `nyholm/psr7`)

The SDK is not coupled to any specific HTTP library — bring your own PSR-18 client.

---

## Installation

```bash
composer require mailgun/mailgun-php symfony/http-client nyholm/psr7
```

> **EU region?** Use `https://api.eu.mailgun.net` as your endpoint (see below).

---

## Quick Start

```php
require 'vendor/autoload.php';

use Mailgun\Mailgun;

// US servers (default)
$mg = Mailgun::create('your-api-key');

// EU servers
$mg = Mailgun::create('your-api-key', 'https://api.eu.mailgun.net');
```

> **Note:** The `$domain` you pass to any API call must match a domain configured in [app.mailgun.com](https://app.mailgun.com/app/domains).

---

## Sending Email

### Simple message

```php
$mg->messages()->send('example.com', [
    'from'    => 'Alice <alice@example.com>',
    'to'      => 'bob@example.com',
    'subject' => 'Hello from Mailgun!',
    'text'    => 'This is a plain-text body.',
    'html'    => '<p>This is an <strong>HTML</strong> body.</p>',
]);
```

### With attachments and tracking options

```php
$mg->messages()->send('example.com', [
    'from'       => 'alice@example.com',
    'to'         => ['bob@example.com', 'carol@example.com'],
    'subject'    => 'Monthly report',
    'text'       => 'Please find the report attached.',
    'attachment' => [['filePath' => '/tmp/report.pdf', 'filename' => 'report.pdf']],
    'o:tracking' => 'yes',
    'o:tag'      => ['monthly', 'report'],
]);
```

### Scheduled delivery

```php
$mg->messages()->send('example.com', [
    'from'           => 'alice@example.com',
    'to'             => 'bob@example.com',
    'subject'        => 'See you tomorrow',
    'text'           => 'Scheduled for tomorrow morning.',
    'o:deliverytime' => 'tomorrow 9am UTC',
]);
```

---

## IP Management

### List all account IPs

```php
$response = $mg->ips()->index();

foreach ($response->getItems() as $ip) {
    echo $ip . PHP_EOL;
}

// Dedicated only
$dedicated = $mg->ips()->index(dedicated: true);

// With full details (pool assignments, subaccount ownership, timestamps)
$detailed = $mg->ips()->listIpsDetailed([
    'pool_id'      => 'my-pool-id', // filter by pool ('any' or 'none' also accepted)
    'subaccount_id'=> 'sub-123',
    'limit'        => 25,
]);

foreach ($detailed->getItems() as $ip) {
    echo "{$ip['address']} — pools: " . implode(', ', $ip['pool_ids']) . PHP_EOL;
}
```

### Inspect a single IP

```php
$ip = $mg->ips()->show('1.2.3.4');

echo $ip->getIp();       // "1.2.3.4"
echo $ip->getRdns();     // reverse DNS
var_dump($ip->getDedicated()); // bool
```

### Assign / remove an IP on a specific domain

```php
// Add IP to a domain
$mg->ips()->assign('example.com', '1.2.3.4');

// Remove IP from a domain
$mg->ips()->unassign('example.com', '1.2.3.4');

// List IPs currently assigned to a domain
$response = $mg->ips()->domainIndex('example.com');
print_r($response->getItems());
```

### Bulk IP operations across all domains

```php
// Assign an IP to every domain in the account (async)
$ref = $mg->ips()->assignIpToAllDomains('1.2.3.4');
echo $ref->getReferenceId(); // track the async operation

// Remove an IP from all domains, replacing it with another
$ref = $mg->ips()->removeIpFromAllDomains('1.2.3.4', alternative: '5.6.7.8');
echo $ref->getMessage();
```

### Find all domains using a specific IP

```php
$response = $mg->ips()->domainsByIp('1.2.3.4', limit: 20, search: 'example');

foreach ($response->getItems() as $domain) {
    echo $domain . PHP_EOL;
}
```

### Dedicated IP band

```php
// Move an account IP into a dedicated IP band
$mg->ips()->placeAccountIpToBand('1.2.3.4');
```

### Request a new dedicated IP

```php
// Check how many dedicated IPs your plan allows
$available = $mg->ips()->numberOfIps();

// Provision a new dedicated IP
$mg->ips()->addDedicatedIp();
```

---

## Dynamic IP Pools (DIPP)

Dynamic IP Pools let you group dedicated IPs and link them to domains, so sending traffic is spread across all IPs in the pool automatically.

### List and inspect pools

```php
// All pools in the account
$response = $mg->ips()->listIpPools();

foreach ($response->getIpPools() as $pool) {
    echo "{$pool['pool_id']} — {$pool['name']}" . PHP_EOL;
    echo "  IPs: " . implode(', ', $pool['ips']) . PHP_EOL;
    echo "  Linked to domains: " . ($pool['is_linked'] ? 'yes' : 'no') . PHP_EOL;
}

// Single pool details
$pool = $mg->ips()->loadDIPPInformation('my-pool-id');

echo $pool->getPoolId();      // "my-pool-id"
echo $pool->getName();        // "Primary sending pool"
echo $pool->getDescription(); // "Main US sending pool"
print_r($pool->getIps());     // ["1.2.3.4", "5.6.7.8"]
var_dump($pool->isLinked());  // bool — whether domains are attached
```

### Create and configure a pool

```php
// Create a new pool
$mg->ips()->createIpPool('Primary Pool', 'Main US outbound pool');

// Modify pool metadata, add/remove IPs, link/unlink domains — all in one call
$mg->ips()->updateIpPool('my-pool-id', [
    'name'          => 'Primary Pool v2',
    'add_ip'        => '9.10.11.12',
    'remove_ip'     => '1.2.3.4',
    'link_domain'   => 'example.com',
    'unlink_domain' => 'old.example.com',
]);
```

### Manage IPs inside a pool

```php
// Add a single IP to a pool
$mg->ips()->addIpToPool('my-pool-id', '9.10.11.12');

// Add multiple IPs at once
$mg->ips()->addIpsToPool('my-pool-id', ['9.10.11.12', '13.14.15.16']);

// Remove an IP from a pool
$mg->ips()->removeIpFromPool('my-pool-id', '1.2.3.4');
```

> When a pool is linked to domains, adding or removing IPs propagates to all linked domains **asynchronously** after the API responds.

### List domains linked to a pool

```php
$response = $mg->ips()->getIpPoolDomains('my-pool-id', limit: 20);

foreach ($response->getDomains() as $domain) {
    echo $domain['name'] . PHP_EOL;
}

// Paginate using the cursor from the previous response
if ($response->getNextPage()) {
    $next = $mg->ips()->getIpPoolDomains('my-pool-id', page: $response->getNextPage());
}
```

### Delete a pool

```php
// Delete without replacement (pool must not be linked to any domains)
$mg->ips()->deleteDIPP('my-pool-id');

// Replace linked domains with a specific IP before deleting
$mg->ips()->deleteDIPP('my-pool-id', replacementIp: '1.2.3.4');

// Replace linked domains with another pool before deleting
$mg->ips()->deleteDIPP('my-pool-id', replacementPoolId: 'backup-pool-id');
```

### Delegate a pool to a subaccount

```php
// Grant a subaccount access to a pool
$mg->ips()->delegateIpPool('my-pool-id', 'sub-account-id');

// Revoke subaccount access
$mg->ips()->revokeDelegatedIpPool('my-pool-id', 'sub-account-id');
```

---

## Analytics

```php
$result = $mg->metrics()->loadMetrics([
    'start'      => 'Sun, 22 Dec 2024 00:00:00 +0000',
    'end'        => 'Sun, 29 Dec 2024 00:00:00 +0000',
    'resolution' => 'day',
    'dimensions' => ['time'],
    'metrics'    => ['accepted_count', 'delivered_count', 'clicked_rate', 'opened_rate'],
    'include_aggregates'  => true,
    'include_subaccounts' => true,
]);

foreach ($result->getItems() as $item) {
    echo $item['dimensions']['time'] . ': ' . $item['metrics']['delivered_count'] . ' delivered' . PHP_EOL;
}
```

---

## Subaccounts

```php
// Create a subaccount
$mg->subaccounts()->create('Marketing Team');

// List all subaccounts
$items = $mg->subaccounts()->index();
print_r($items->getItems());

// Enable / disable
$mg->subaccounts()->enable($subAccountId);
$mg->subaccounts()->disable($subAccountId);
```

### Make API calls on behalf of a subaccount

```php
// Pass the subaccount ID as the third argument to Mailgun::create()
$mg = Mailgun::create('your-api-key', 'https://api.mailgun.net', $subAccountId);

// All subsequent calls are scoped to that subaccount
$mg->messages()->send('example.com', [...]);
```

---

## Response Handling

All API methods return typed model objects with IDE-friendly getters by default.

```php
$domain = $mg->domains()->show('example.com');

foreach ($domain->getInboundDNSRecords() as $record) {
    echo $record->getType() . ': ' . $record->getValue() . PHP_EOL;
}
```

### Array responses

Prefer raw arrays? Inject `ArrayHydrator`:

```php
use Mailgun\Hydrator\ArrayHydrator;
use Mailgun\HttpClient\HttpClientConfigurator;

$configurator = new HttpClientConfigurator();
$configurator->setApiKey('your-api-key');

$mg = new Mailgun($configurator, new ArrayHydrator());

$data = $mg->domains()->show('example.com');
// $data is now a plain associative array
```

### Raw PSR-7 response

Need the raw response? Use `NoopHydrator` — **note: no exceptions are thrown on non-200 responses when using this hydrator.**

```php
use Mailgun\Hydrator\NoopHydrator;

$mg = new Mailgun($configurator, new NoopHydrator());
$response = $mg->messages()->send('example.com', [...]);
// $response is a Psr\Http\Message\ResponseInterface
echo $response->getStatusCode();
```

---

## Debugging

Route traffic through Mailgun's [Postbin](http://bin.mailgun.net) to inspect what the SDK sends:

```php
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\Hydrator\NoopHydrator;

$configurator = new HttpClientConfigurator();
$configurator->setEndpoint('http://bin.mailgun.net/aecf68de'); // replace with your bin ID
$configurator->setApiKey('your-api-key');
$configurator->setDebug(true);

$mg = new Mailgun($configurator, new NoopHydrator());

$mg->messages()->send('example.com', [
    'from'    => 'alice@example.com',
    'to'      => 'bob@example.com',
    'subject' => 'Debug test',
    'text'    => 'Checking what hits the wire.',
]);
```

### Custom HTTP requests

```php
$client = $mg->httpClient();

$client->httpGet('/v3/domains', ['limit' => 5]);
$client->httpPost('/v3/some/path', ['key' => 'value']);
$client->httpPut('/v3/some/path', ['key' => 'value']);
$client->httpDelete('/v3/some/path');
```

---

## Framework Integration

| Framework | Package |
|-----------|---------|
| Symfony | [tehplague/swiftmailer-mailgun-bundle](https://github.com/tehplague/swiftmailer-mailgun-bundle) |
| Yii2 | [katanyoo/yii2-mailgun-mailer](https://github.com/katanyoo/yii2-mailgun-mailer) |
| CakePHP | [narendravaghela/cakephp-mailgun](https://github.com/narendravaghela/cakephp-mailgun) |
| Drupal | [drupal/mailgun](https://www.drupal.org/project/mailgun) |
| Laravel | Built-in — see [Laravel Mail docs](https://laravel.com/docs/mail#mailgun-driver) |

---

## Contributing

This is an open-source project under the MIT license, maintained by Mailgun and the community.

### Running the tests

```bash
git clone git@github.com:mailgun/mailgun-php.git
cd mailgun-php
composer install
composer test
```

### Ways to help

- Test the `dev-master` branch and open issues for anything broken
- Review open pull requests
- Add tests for untested endpoints
- Improve documentation and examples

---

## Support

- **Documentation:** [documentation.mailgun.com](https://documentation.mailgun.com/docs/mailgun/api-reference)
- **Issues:** [GitHub Issues](https://github.com/mailgun/mailgun-php/issues)
- **Account support:** [app.mailgun.com/support](https://app.mailgun.com/support)
- **More examples:** [doc/examples.md](doc/examples.md)
