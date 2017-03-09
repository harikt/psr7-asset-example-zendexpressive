<?php
namespace App\Config;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;
use Zend\Expressive\Router\Route;

class Common implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        $di->set('Interop\Http\Factory\ResponseFactoryInterface', $di->lazyNew('Http\Factory\Diactoros\ResponseFactory'));
        $di->params['Hkt\Psr7Asset\AssetResponder']['responseFactory'] = $di->lazyGet('Interop\Http\Factory\ResponseFactoryInterface');
        $di->set('Hkt\Psr7Asset\Router', $di->lazyNew('Hkt\Psr7Asset\Router'));
        $di->set('Hkt\Psr7Asset\AssetLocator', $di->lazyNew('Hkt\Psr7Asset\AssetLocator'));

        $di->params['Hkt\Psr7Asset\AssetService']['locator'] = $di->lazyGet('Hkt\Psr7Asset\AssetLocator');

        $di->params['Hkt\Psr7Asset\AssetAction'] = array(
            'domain' => $di->lazyNew('Hkt\Psr7Asset\AssetService'),
            'responder' => $di->lazyNew('Hkt\Psr7Asset\AssetResponder'),
            'router' => $di->lazyGet('Hkt\Psr7Asset\Router'),
        );

        $di->set('Hkt\Psr7Asset\AssetAction', $di->lazyNew('Hkt\Psr7Asset\AssetAction'));
        $di->set('TwigEnvironment', $di->lazyNew('Zend\Expressive\Twig\TwigEnvironmentFactory'));
        $di->set('Zend\Expressive\Twig\TwigRendererFactory', $di->lazyNew('Zend\Expressive\Twig\TwigRendererFactory'));
        $di->set('Zend\Expressive\Template\TemplateRendererInterface', $di->lazyGetCall('Zend\Expressive\Twig\TwigRendererFactory', '__invoke', $di));
    }

    public function modify(Container $di)
    {
        $router = $di->get('Zend\Expressive\Router\RouterInterface');
        // $router->addRoute(new Route('/asset/{vendor}/{package}/{file:.*}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset'));
        $route = new Route('/asset/{vendor}/{package}/{file}', 'Hkt\Psr7Asset\AssetAction', ['GET'], 'hkt/psr7-asset');
        $route->setOptions([
            'tokens' => [
                'file' => '(.*)'
            ]
        ]);
        $router->addRoute($route);
        $router->addRoute(new Route('/', 'Hkt\Psr7AssetExample\Middleware\Welcome', ['GET'], 'hkt/psr7-asset-example:welcome'));
        // Try modifying the asset to a different url
        // $assetLocator = $di->get('Hkt\Psr7Asset\AssetLocator');
        // $rootPath = dirname(dirname(dirname(__DIR__)));
        // $assetLocator->set('hkt/psr7-asset-example/images/white-image.png', $rootPath . '/public/psr7-asset-example/white-image.png');
    }
}
