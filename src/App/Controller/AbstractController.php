<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Apache2BasicAuth\Service as HTService;

/**
 * Class AbstractController
 * @category Controller
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
abstract class AbstractController
{

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var \Apache2BasicAuth\Service
     */
    protected $htService;

    /**
     * Constructor
     * @param \Silex\Application $app Application instance
     */
    public function __construct(Application $app)
    {
        $this->app       = $app;
        $this->htService = new HTService($app['config']['htpasswd'], $app['config']['htgroups']);
    }

    /**
     * Render a Response using Twig
     * @param string $template The template
     * @param array  $data     Array of data to the template
     * @return string
     */
    protected function renderTemplate($template, $data = array())
    {
        return $this->app['twig']->render($template . '.html.twig', $data);
    }

    /**
     * Render a template using Twig passing default data
     * @param string $template The template
     * @param array  $data     Array of data to the template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function render($template, $data = array())
    {
        return new Response(
            $this->renderTemplate($template, $data),
            array_key_exists('http-code', $data) ? $data['http-code'] : Response::HTTP_OK
        );
    }
}
