<?php

$header = <<<TXT
Copyright (C) 2013 Mailgun

This software may be modified and distributed under the terms
of the MIT license. See the LICENSE file for details.
TXT;

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests');


return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@Symfony' => false,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_empty_phpdoc' => false,
        'no_superfluous_phpdoc_tags' => false,
        'phpdoc_separation' => false,
        'no_unneeded_final_method' => false, # prevent phpstan divergence
        'header_comment' => [
            'comment_type' => 'comment',
            'header' => $header,
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
    ])
    ->setFinder($finder)
;
