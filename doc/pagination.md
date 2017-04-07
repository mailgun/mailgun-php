# Pagination

Some API endpoints do support pagination. 

```php

/** @var Mailgun\Model\Tag\IndexReponse $response */
$reponse = $mailgun->tags()->index('example.com');

// Parse through the first response
// ...

$nextResponse = $mailgun->tags()->nextPage($response);
$previousResponse = $mailgun->tags()->previousPage($response);
$firstResponse = $mailgun->tags()->firstPage($response);
$lastResponse = $mailgun->tags()->lastPage($response);
```
