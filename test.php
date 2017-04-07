<?php

include "vendor/autoload.php";

$mailgun = \Mailgun\Mailgun::create('key-f22961235cdf7aaef78a9228c710f12c');
$bounces = $mailgun->suppressions()->bounces()->index('sandbox40c4b0745b8a4461959950ccf9c0a1ea.mailgun.org');
$x = 2;
