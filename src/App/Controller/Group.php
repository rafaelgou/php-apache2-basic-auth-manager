<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Apache2AuthManager\Passwd;
use Apache2AuthManager\Group;

class Group extends AbstractController
{

    public function add(Request $request)
    {
        return $this->render(
            'index',
            array(
                'title' => 'Add Group'
            )
        );
    }

    public function addSave(Request $request)
    {
        return $this->render(
            'index',
            array(
                'title' => 'Add Group - SAVE'
            )
        );
    }


    public function edit(Request $request, $groupname)
    {
        return $this->render(
            'index',
            array(
                'title' => "Edit Group - $groupname"
            )
        );
    }

    public function editSave(Request $request, $groupname)
    {
        return $this->render(
            'index',
            array(
                'title' => "Edit Group - $groupname"
            )
        );
    }

}
