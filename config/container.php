<?php

use App\ExpressiveAuraConfig;
use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

require_once __DIR__ . '/ExpressiveAuraConfig.php';
require_once __DIR__ . '/ExpressiveAuraDelegatorFactory.php';

// Load configuration
$config = require __DIR__ . '/config.php';

// Build container
$builder = new ContainerBuilder();

return $builder->newConfiguredInstance([
    new ExpressiveAuraConfig(is_array($config) ? $config : []),
    Hkt\Psr7AssetExample\Config\Common::class,
    App\Config\Common::class,
]);
