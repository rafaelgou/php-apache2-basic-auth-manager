<?php
/*
 * This file is part of the PHP Apache2 Basic Auth Manager package.
 *
 * (c) Rafael Goulart <rafaelgou@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Silex\Application;

/**
 * Class Group Controller
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
class Route
{

    /**
     * @var string
     */
    protected $httpMethod;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $target;

    /**
     * Constructor
     * @param string $httpMethod Method get/post/put/delete
     * @param string $path       URI Path
     * @param string $target     Target Controller:method
     * @return Response
     */
    public function __construct($httpMethod = null, $path = null, $target = null)
    {
        $this->httpMethod = strtolower($httpMethod);
        $this->path       = $path;
        $this->target     = $target;
    }

    /**
     * Get HTTP Method
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Set HTTP Method
     * @param string $httpMethod Method get/post/put/delete
     * @return Route
     */
    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }

    /**
     * Get Path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set Path
     * @param string $path URI Path
     * @return Route
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get HTTP Method
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set Target
     * @param string $target Target Controller:method
     * @return Route
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Register route
     * @param Application $app The Silex Application
     * @return Route
     */
    public function registerRoute(Application $app)
    {
        $httpMethod = $this->getHttpMethod();
        $target     = explode(':', $this->getTarget());
        $controller = $target[0];
        $ctrl       = explode('\\', $target[0]);
        $action     = $target[1];

        array_walk(
            $ctrl,
            function (&$item, $key) {
                $item = strtolower($item);
            }
        );
        $ctlrShort = implode('.', $ctrl);
        if (!isset($app[$ctlrShort])) {
            $app[$ctlrShort] = function () use ($app, $controller) {
                $controller = '\\'.$controller;

                return new $controller($app);
            };
        }

        $app->$httpMethod($this->getPath(), "{$ctlrShort}:{$action}");

        return $this;
    }
}
