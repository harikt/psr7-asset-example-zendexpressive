<?php

use App\ExpressiveAuraConfig;
use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

require_once __DIR__ . '/ExpressiveAuraConfig.php';
require_once __DIR__ . '/ExpressiveAuraDelegatorFactory.php';

// Load configuration
$config = require __DIR__ . '/config.php';

// module loader
$configClasses = [
    Hkt\Psr7AssetExample\Config\Common::class,
    App\Config\Common::class,
];

// Build container
$builder = new ContainerBuilder();
$container = $builder->newInstance();
$configs = [];
foreach ($configClasses as $configClass) {
    if (is_string($configClass)) {
        $configClass = new $configClass;
    }

    if (! $configClass instanceof ContainerConfigInterface) {
        continue;
    }

    $configClass->define($container);
    $configs[] = $configClass;
}

// ExpressiveConfig
$configClass = new ExpressiveAuraConfig(is_array($config) ? $config : []);
$configClass->define($container);
$configs[] = $configClass;

$container->lock();

foreach ($configs as $configuration) {
    $configuration->modify($container);
}

return $container;
