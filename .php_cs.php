<?php

// Design pattern Iterator
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'var', 'bin', 'public']);

return PhpCsFixer\Config::create()
    ->setRules(
        [
            '@Symfony' => true,
            'phpdoc_align' => false,
        ]
    )
    ->setRiskyAllowed(true)
    ->setLineEnding(PHP_EOL)
    ->setUsingCache(true)
    ->setFinder($finder);
