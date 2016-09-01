<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class User extends AbstractController
{

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

    public function edit(Request $request, $username)
    {
        $user = $this->htService->findUser($username);
        if (null === $user) {
            throw new \Exception('User not found', 404);
        }

        $form = $this->getForm($user);

        if ($request->getMethod() === 'POST') {
          $form->handleRequest($request);

          if ($form->isValid()) {
              $user = $form->getData();
              $user->setUsername($username);
              if ($user->getPassword() > 0) {
                  if (strlen($user->getPassword()) < $config['minPassword'] ) {
                      $this->app['session']->getFlashBag()->add('error', "Please fill Password (min {$config['minPassword']} characters).");
                      return $this->app->redirect("/user/{$username}/edit");
                  }
              }
              $this->htService->persist($user)->write();
              $this->app['session']->getFlashBag()->add('success', "User {$username} updated successfuly.");

              return $this->app->redirect('/');
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

    protected function getForm($data = array())
    {
        $groupnames = $this->htService->getGroupNames();
        $groupChoices = array_combine($groupnames, $groupnames);

        return $this->app['form.factory']->createBuilder(FormType::class, $data)
            ->add('username', null, array(
                'required' => true
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
                'multiple' => true
            ))
            ->getForm();
    }

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

}
