<?php

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in([__DIR__])
    ->append([__FILE__]);

$options = [
    'finder'     => $finder,
    'usingCache' => false,
];

$override = [
    'strict_comparison' => false,
    'strict_param'      => false,
];

return Factory::create(new CodeIgniter4(), $override, $options)->forProjects();
