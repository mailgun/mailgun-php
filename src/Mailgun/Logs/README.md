Mailgun - Logs
====================

This is the Mailgun PHP *Logs* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Logs" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Logs object on the Logs API endpoint.
$logs = $mgClient->Logs();

# Finally, get the 50 most recent log items.
$result = $logs->getLogs(50, 0);

```

Available Functions
-------------------

`getLogs(int $limit, int $skip)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-logs.html) for more information.