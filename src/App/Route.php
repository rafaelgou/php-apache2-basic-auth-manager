<?php

namespace App;

use Silex\Application;

class Route
{

    /**
     * @var string
     */
    protected $httpMethod;

    protected $path;

    protected $target;

    public function __construct ($httpMethod = null, $path = null, $target = null)
    {
        $this->httpMethod = $httpMethod;
        $this->path       = $path;
        $this->target     = $target;
    }

    public function getHttpMethod()
    {
       return $this->httpMethod;
    }

    public function setHttpMethod($httpMethod)
    {
       $this->httpMethod = $httpMethod;
       return $this;
    }

    public function getPath()
    {
       return $this->path;
    }

    public function setPath($path)
    {
       $this->path = $path;
       return $this;
    }

    public function getTarget()
    {
       return $this->target;
    }

    public function setTarget($target)
    {
       $this->target = $target;
       return $this;
    }

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
                $controller = '\\' . $controller;
                return new $controller($app);
            };
        }

        $app->$httpMethod($this->getPath(), "{$ctlrShort}:{$action}");

        return $this;
    }
}
