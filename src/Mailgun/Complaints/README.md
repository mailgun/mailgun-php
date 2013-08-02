Mailgun - Complaints
====================

This is the Mailgun PHP *Complaints* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Complaints" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Complaints object on the Complaints API endpoint.
$complaints = $mgClient->Complaints();

# Finally, add a complaint to the Spam Complaints table.
$result = $complaints->addAddress("junk@samples.mailgun.org");

```

Available Functions
-------------------

`addAddress(string $spamAddress)`  

`deleteAddress(string $spamAddress)`  

`getComplaint(string $spamAddress)`  

`getComplaints(int $limit, int $skip)`  

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-complaints.html) for more information.