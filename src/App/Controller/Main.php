<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Apache2AuthManager\Passwd;
use Apache2AuthManager\Group;

class Main extends AbstractController
{

    public function index(Request $request)
    {
        $users = $this->passwdHandler->getUsersAndGroups($this->groupHandler);
        ksort($users);
        $groups = $this->groupHandler->getGroups();
        ksort($groups);

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
