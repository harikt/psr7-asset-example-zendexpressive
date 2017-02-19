<?php

use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

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

// Inject config
$container->set('config', $config);

// Inject factories
foreach ($config['dependencies']['factories'] as $name => $object) {
    $container->set($object, $container->lazyNew($object));
    $container->set($name, $container->lazyGetCall($object, '__invoke', $container));
}

// Inject invokables
foreach ($config['dependencies']['invokables'] as $name => $object) {
    $container->set($name, $container->lazyNew($object));
}

$container->lock();

foreach ($configs as $configuration) {
    $configuration->modify($container);
}

return $container;
