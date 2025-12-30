<?php

use Dotenv\Dotenv;
use GSpataro\Application\Component;
use GSpataro\DependencyInjection\Container;

// Include directories aliases

require_once __DIR__ . "/directories.php";

// Include composer autoloader

require_once DIR_VENDOR . "/autoload.php";

// Initialize nunomaduro/collision component

(new \NunoMaduro\Collision\Provider())->register();

// Initialize vlucas/phpdotenv

$dotenv = Dotenv::createImmutable(DIR_ROOT);
$dotenv->load();

// Initialize dependency injection container

$app = new Container();
$app->loadComponents([
    Component\LocalizationComponent::class,
    Component\ProjectComponent::class,
    Component\HighlightComponent::class,
    Component\AssetsComponent::class,
    Component\TwigComponent::class,
    Component\MarkdownComponent::class,
    Component\LibraryComponent::class,
    Component\FinderComponent::class,
    Component\PagesComponent::class,
    Component\ContractorComponent::class,
    Component\CLIComponent::class
]);
$app->boot();
