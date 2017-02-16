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
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Apache2BasicAuth\Model\User as UserModel;
use Respect\Validation\Validator as V;

/**
 * Class User Controller
 * @category Controller
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
class User extends AbstractController
{

    /**
     * Add a record
     * @param Request $request The HTTP Request
     * @return Response
     */
    public function add(Request $request)
    {
        $user = $this->htService->createUser();
        $form = $this->getForm($user);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $form->getData();
                $this->htService->persist($user)->write();
                $this->app['session']->getFlashBag()->add('success', "User {$username} added successfuly.");

                return $this->app->redirect('/');
            }
        }

        return $this->render(
            'user-edit',
            array(
                'action' => 'add',
                'title'  => 'Add User',
                'form'   => $form->createView(),
            )
        );
    }

    /**
     * Edit a record
     * @param Request $request  The HTTP Request
     * @param string  $username User name
     * @throws \Exception User not found
     * @return Response
     */
    public function edit(Request $request, $username)
    {
        $user = $this->htService->findUser($username);
        if (null === $user) {
            throw new \Exception('User not found', 404);
        }

        $form = $this->getForm($user);
        #$validator = false;

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $user = $form->getData();
                $user->setUsername($username);

                if ($user->getPassword() > 0) {
                    if (strlen($user->getPassword()) < $config['minPassword']) {
                        $this->app['session']->getFlashBag()->add(
                            'error',
                            "Please fill Password (min {$config['minPassword']} characters)."
                        );

                        return $this->app->redirect("/user/{$username}/edit");
                    }
                }
                #if (!$validator->validate($user)) {
                  $this->htService->persist($user)->write();
                  $this->app['session']->getFlashBag()->add('success', "User {$username} updated successfuly.");

                  return $this->app->redirect('/');
                #}
            }
        }

        return $this->render(
            'user-edit',
            array(
                'action'   => 'edit',
                'title'    => "Edit User - $username",
                'username' => $username,
                'form'     => $form->createView(),
            )
        );
    }

    /**
     * Delete a record
     * @param Request $request  The HTTP Request
     * @param string  $username User name
     * @throws \Exception User not found
     * @return Response
     */
    public function delete(Request $request, $username)
    {
        $user = $this->htService->findUser($username);
        if (null === $user) {
            throw new \Exception('User not found', 404);
        }

        $this->htService->removeUser($user)->write();
        $this->app['session']->getFlashBag()->add('success', "User {$username} deleted successfuly.");

        return $this->app->redirect('/');
    }

    /**
     * Get Form instance
     * @param array $data Form initial data
     * @return Form
     */
    protected function getForm($data = array())
    {
        $groupnames = $this->htService->getGroupNames();
        $groupChoices = array_combine($groupnames, $groupnames);

        return $this->app['form.factory']->createBuilder(FormType::class, $data)
            ->add('username', null, array(
                'required' => true,
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => false,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('groups', ChoiceType::class, array(
                'choices' => $groupChoices,
                'expanded' => true,
                'multiple' => true,
            ))
            ->getForm();
    }

}
