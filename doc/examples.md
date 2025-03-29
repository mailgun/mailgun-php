## Domain Keys Example

```php
<?php
require 'vendor/autoload.php';

use Mailgun\Mailgun;

$mgClient = Mailgun::create('xxx');
$domain = "xxx.mailgun.org";

try {
    $res = $mgClient->domainKeys()->listKeysForDomains();
    print_r($res);
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}

try {
    $res = $mgClient->domainKeys()->deleteDomainKey($domain, 'xxx');
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}

try {
    $res = $mgClient->domainKeys()->listDomainKeys($domain);
    print_r($res);
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}
try {
    $res = $mgClient->domainKeys()->createDomainKey($domain, sprintf('key-%s', time()));
    print_r($res);
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}

try {
    $res = $mgClient->domainKeys()->deleteDomainKey($domain, 'key-xxx');
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}


try {
    $res = $mgClient->domainKeys()->createDomainKey($domain, sprintf('key-%s', time()));
    print_r($res);
} catch (Throwable $t) {
    print_r($t->getMessage());
    print_r($t->getTraceAsString());
}

```
