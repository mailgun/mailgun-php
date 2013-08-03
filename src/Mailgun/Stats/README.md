Mailgun - Stats
====================

This is the Mailgun PHP *Stats* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Stats" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org");

# Next, instantiate a Stats object on the Stats API endpoint.
$stats = $mgClient->Stats();

# Next, get the last 50 stats.
$stats->getStats(array('limit' => 50, 'skip' => 0, 'event' => 'sent'));
```

Available Functions
-------------------

`deleteTag(string $tag)`  

`getStats(array $filterParams)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-stats.html) for more information.