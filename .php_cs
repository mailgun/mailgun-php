<?php

$header = <<<TXT
Copyright (C) 2013 Mailgun

This software may be modified and distributed under the terms
of the MIT license. See the LICENSE file for details.
TXT;

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests');

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'strict_param' => false,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'header_comment' => [
            'commentType' => 'comment',
            'header' => $header,
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
    ])
    ->setFinder($finder)
;
