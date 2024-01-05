<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';

$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();

// Rebuild the cache.
$kernel->invalidateContainer();
$kernel->rebuildContainer();

echo "Cache rebuild complete.";
