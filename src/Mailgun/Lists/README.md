Mailgun - Lists
====================

This is the Mailgun PHP *Lists* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Lists" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Lists object on the Lists API endpoint.
$lists = $mgClient->Lists();

# Finally, get 50 results and store in $result.
$result = $lists->getLists(50, 0);

```

Available Functions
-------------------

`getLists(int $limit, int $skip)`  

`getList(string $listAddress)`  

`addList(string $listAddress, string $name, string $description, string $access_level)`  

`updateList(string $listAddress, string $name, string $description, string $access_level)`  

`deleteList(string $listAddress)`  

`getListMembers(string $listAddress, array $filterParams)`  

`getListMember(string $listAddress, string $memberAddress)`  

`addListMember(string $listAddress, string $memberAddress, string $name, array $vars, bool $subscribed, bool $upsert)`  

`updateListMember(string $listAddress, string $memberAddress, string $name, array $vars, bool $subscribed)`  

`deleteListMember(string $listAddress, string $memberAddress)`  

`getListStats(string $listAddress)`  



More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-mailinglists.html) for more information.