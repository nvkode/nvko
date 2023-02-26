<?php

/**
 * Loader for Doctrine Manager in PHPStan.
 */

require __DIR__ . '/../../vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

$kernel->boot();

return $kernel->getContainer()->get('doctrine')->getManager();
