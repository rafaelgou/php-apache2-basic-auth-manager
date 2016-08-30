<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Apache2AuthManager\Passwd;
use Apache2AuthManager\Group;

class User extends AbstractController
{

    public function add(Request $request)
    {
        $users = $this->passwdHandler->getUsersAndGroups($this->groupHandler);
        ksort($users);
        $groups = $this->groupHandler->getGroups();
        ksort($groups);

        return $this->render(
            'index',
            array(
                'title' => 'Add User',
                'users' => $users,
                'groups' => $groups
            )
        );
    }

    public function addSave(Request $request)
    {
        $users = $this->passwdHandler->getUsersAndGroups($this->groupHandler);
        ksort($users);
        $groups = $this->groupHandler->getGroups();
        ksort($groups);

        return $this->render(
            'index',
            array(
                'title' => 'Add User',
                'users' => $users,
                'groups' => $groups
            )
        );
    }


    public function edit(Request $request, $username)
    {
        $userGroups      = $this->groupHandler->getGroupsByUser($username);
        $availableGroups = $this->groupHandler->getGroups();
        ksort($availableGroups);

        $groups = array();
        foreach ($availableGroups as $groupname => $users) {
          $groups[$groupname] = in_array($groupname, $userGroups);
        }

        if ($request->getMethod() === 'POST') {
            $password = $request->request->get('password', false);
            if (strlen($password) > 0) {
                if (strlen($password) < $config['minPassword'] ) {
                    $this->app['session']->getFlashBag()->add('error', "Please fill Password (min {$config['minPassword']} characters).");
                    header("Location:/user/{$username}/edit");
                } else {
                    $this->passwdHandler->editUser($username, $password);
                    $this->groupHandler->setGroupsToUser($username, $request->request->get('groups', array()));
                    $this->app['session']->getFlashBag()->add('success', "User {$username} updated successfuly.");
                    header("Location:/");
                    exit;
                }
            } else {
                $this->groupHandler->setGroupsToUser($username, $request->request->get('groups', array()));
                $this->app['session']->getFlashBag()->add('success', "User {$username} updated successfuly.");
                header("Location:/");
                exit;
            }
        }

        return $this->render(
            'user-edit',
            array(
                'title'    => "Edit User - $username",
                'username' => $username,
                'groups'   => $groups
            )
        );
    }

    public function editSave(Request $request, $username)
    {
    }

}
