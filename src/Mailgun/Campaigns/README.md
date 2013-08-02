Mailgun - Campaigns
===================

This is the Mailgun PHP *Campaigns* endpoint. 

The below assumes you've already installed the Mailgun PHP SDK in to your project. If not, go back to the master README for instructions.

Usage
-------------
Here's how to use the "Campaign" API endpoint:

```php
# First, instantiate the client with your PUBLIC API credentials and domain. 
$mgClient = new MailgunClient("pubkey-5ogiflzbnjrljiky49qxsiozqef5jxp7", "samples.mailgun.org");

# Next, instantiate a Campaign object on the Campaign API endpoint.
$campaigns = $mgClient->Campaigns();

$ Finally, get a list of campaigns, limit 5, skip 0. 
$campaigns->getCampaigns(5, 0);

```

Available Functions
-------------------

`getCampaigns(int $limit, int $skip)`  

`getCampaign(string $campaignId)`  

`addCampaign(string $name, string $id)`  

`updateCampaign(string $campaignId, string $name, string $id)`  

`deleteCampaign(string $campaignId)`  

`getCampaignEvents(string $campaignId, array $filterParams)`  

`getCampaignStats(string $campaignId, array $filterParams)`  

`getCampaignClicks(string $campaignId, array $filterParams)`  

`getCampaignOpens(string $campaignId, array $filterParams)`  

`getCampaignUnsubscribes(string $campaignId, array $filterParams)`  

`getCampaignComplaints(string $campaignId, array $filterParams)`  

$filterParams are unique to the endpoint being called. See the documentation below for specifics.

More Documentation
------------------
See the official [Mailgun Docs](http://documentation.mailgun.com/api-campaigns.html) for more information.