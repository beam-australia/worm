<?php

use Beam\Worm\Factories\Builder;
use Tests\RegisterFixtures;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once getenv('WP_PHPUNIT__DIR') . '/includes/functions.php';

/**
 * test set up, plugin activation, etc.
 */
tests_add_filter('muplugins_loaded', function () {

    RegisterFixtures::postTypes();

    RegisterFixtures::taxonomies();

    $path = WORM_DATABASE_PATH . '/factories';

    Builder::build($path);
});

/**
 *  Start up the WP testing environment.
 */
require getenv('WP_PHPUNIT__DIR') . '/includes/bootstrap.php';
