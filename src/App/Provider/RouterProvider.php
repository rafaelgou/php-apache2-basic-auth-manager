<?php
/*
 * This file is part of the PHP Apache2 Basic Auth Manager package.
 *
 * (c) Rafael Goulart <rafaelgou@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Route;

/**
 * Router Provider
 * @category Provider
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
class RouterProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * Register
     * @param Application $app The App
     * @return void
     */
    public function register(Container $app)
    {
        $app['app.routing'] = array(
            new Route('get', '/', 'App\Controller\Main:index'),
            new Route('get', '/samplehtaccess', 'App\Controller\Main:sampleHtaccess'),
            new Route('get', '/user/add', 'App\Controller\User:add'),
            new Route('post', '/user/add', 'App\Controller\User:add'),
            new Route('get', '/user/{username}/edit', 'App\Controller\User:edit'),
            new Route('post', '/user/{username}/edit', 'App\Controller\User:edit'),
            new Route('get', '/user/{username}/delete', 'App\Controller\User:delete'),
            new Route('get', '/group/add', 'App\Controller\Group:add'),
            new Route('post', '/group/add', 'App\Controller\Group:add'),
            new Route('get', '/group/{groupname}/edit', 'App\Controller\Group:edit'),
            new Route('post', '/group/{groupname}/edit', 'App\Controller\Group:edit'),
            new Route('get', '/group/{groupname}/delete', 'App\Controller\Group:delete'),
        );
    }

    /**
     * Boot
     * @param Application $app The App
     * @return void
     */
    public function boot(Application $app)
    {
        foreach ($app['app.routing'] as $route) {
            $route->registerRoute($app);
        }

        $app->error(function (\Exception $e, Request $request, $code) use ($app) {
            switch ($e->getCode()) {
                case 404:
                    $title = 'Error 404 - '.$e->getMessage();
                    break;
                default:
                    $title = "Error $code - We are sorry, but something went terribly wrong. ";
            }

            return $app['twig']->render(
                'error.html.twig',
                array(
                    'title' => $title,
                    'error' => $app['debug'] ? $e->getTraceAsString() : false,
                )
            );
        });
    }
}
