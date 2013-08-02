Mailgun - Routes
====================

This is the Mailgun PHP *Routes* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Routes" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Routes object on the Routes API endpoint.
$routes = $mgClient->Routes();

# Next, add the new route.
$routes->addRoute(0, "Match defined recipient", "match_recipient(\"^chris\+(.*)@example.com$\")", "forward(\"mbx@externaldomain.com\")");
```

Available Functions
-------------------

`getRoutes(int $limit, int $skip)`  

`getRoute(string $routeId)`  

`addRoute(int $priority, string $description, string $expression, string $action)`  

`updateRoute(string $routeId, int $priority, string $description, string $expression, string $action)`  

`deleteRoute(string $routeId)`  


More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-routes.html) for more information.