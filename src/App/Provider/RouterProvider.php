<?php
/**
 * @category Provider
 * @package  App\Provider
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 * @license  Private <http://none.de>
 * @link     none
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
 *
 * @category Provider
 * @package  App\Provider
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 * @license  Private <http://none.de>
 * @link     none
 */
class RouterProvider implements  ServiceProviderInterface, BootableProviderInterface
{
    /**
     * Register
     *
     * @param Application $app The App
     *
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
            new Route('get', '/group/add', 'App\Controller\User:add'),
            new Route('post', '/group/add', 'App\Controller\User:addSave'),
            new Route('get', '/group/{username}/edit', 'App\Controller\User:edit'),
            new Route('post', '/group/{username}/edit', 'App\Controller\User:editSave'),
        );
    }

    /**
     * Boot
     *
     * @param Application $app The App
     *
     * @return void
     */
    public function boot(Application $app)
    {
        foreach($app['app.routing'] as $route) {
          $route->registerRoute($app);
        }

        $app->error(function (\Exception $e, Request $request, $code) use ($app) {
            switch ($code) {
                case 404:
                    $title = 'Error 404 - The requested page could not be found.';
                    break;
                default:
                    $title = "Error $code - We are sorry, but something went terribly wrong.";
            }

            return $app['twig']->render(
                'error.html.twig',
                array(
                    'title' => $title,
                    'error' => $app['debug'] ? $e->getTraceAsString() : false
                )
            );
        });

    }



}
