<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class Main extends AbstractController
{

    public function index(Request $request)
    {
        $users = $this->htService->getUsers();
        $groups = $this->htService->getGroups();

        return $this->render(
            'index',
            array(
                'title' => 'Dashboard',
                'users' => $users,
                'groups' => $groups
            )
        );
    }

    public function sampleHtaccess(Request $request)
    {
        return $this->render(
            'samplehtaccess',
            array(
                'title' => 'Sample .htaccess'
            )
        );
    }


}
