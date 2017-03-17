# psr7-asset-example-zendexpressive
Example project ( in zend expressive ) which integrates psr7-asset and psr7-asset-example ( module )

## Installation

```
git clone https://github.com/harikt/psr7-asset-example-zendexpressive.git
cd psr7-asset-example-zendexpressive
composer install
```

Experiment yourself with the `Hkt\Psr7Asset\AssetLocator` to change / serve different images.

Example :

```
<?php
// src/App/Config/Common.php
$assetLocator = $di->get('Hkt\Psr7Asset\AssetLocator');
$rootPath = dirname(dirname(dirname(__DIR__)));
$assetLocator->set('hkt/psr7-asset-example/images/white-image.png', $rootPath . '/public/psr7-asset-example/white-image.png');
```

## Caching Assets

Assets from different `vendor/package` can be cached to the document root,
so it can be served by the server.

```bash
php bin/console.php hkt:psr7-asset:cache ./public
```
