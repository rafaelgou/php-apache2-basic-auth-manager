<?php
/*
 * This file is part of the PHP Apache2 Basic Auth Manager package.
 *
 * (c) Rafael Goulart <rafaelgou@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Main Controller
 * @category Controller
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
class Main extends AbstractController
{

    /**
     * Index page
     * @param Request $request The HTTP Request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render(
            'index',
            array(
                'title' => 'Dashboard',
                'users' => $this->htService->getUsers(),
                'groups' => $this->htService->getGroups(),
            )
        );
    }

    /**
     * Sample HTAccess page
     * @param Request $request The HTTP Request
     * @return Response
     */
    public function sampleHtaccess(Request $request)
    {
        return $this->render(
            'samplehtaccess',
            array(
                'title' => 'Sample .htaccess',
            )
        );
    }
}
