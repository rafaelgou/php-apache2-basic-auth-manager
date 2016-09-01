<?php

namespace App\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Group extends AbstractController
{
    public function add(Request $request)
    {
        $group = $this->htService->createGroup();
        $form = $this->getForm($group);

        if ($request->getMethod() === 'POST') {
          $form->handleRequest($request);

          if ($form->isValid()) {
              $group = $form->getData();
              $this->htService->persist($group)->write();
              $this->app['session']->getFlashBag()->add('success', "Group {$group->groupname} added successfuly.");
              return $this->app->redirect('/');
          }
        }

        return $this->render(
            'group-edit',
            array(
                'action' => 'add',
                'title'  => 'Add Group',
                'form'   => $form->createView(),
            )
        );
    }

    public function edit(Request $request, $groupname)
    {
        $group = $this->getObject(array(
            'groupname' => $groupname,
            'users'     => $this->groupHandler->getGroup($groupname),
        ));
        $form = $this->getForm($group);

        if ($request->getMethod() === 'POST') {
          $form->handleRequest($request);

          if ($form->isValid()) {
              $group = $form->getData();
              $this->groupHandler->setUsersToGroup($groupname, $group->users);
              $this->app['session']->getFlashBag()->add('success', "Group {$groupname} added successfuly.");
              return $this->app->redirect('/');
          }
        }

        return $this->render(
            'group-edit',
            array(
                'action'    => 'edit',
                'title'     => "Edit Group - $groupname",
                'groupname' => $groupname,
                'form'      => $form->createView(),
            )
        );
    }

    protected function getForm($data = array())
    {
        $usernames = $this->htService->getUsernames();
        $userChoices = array_combine($usernames, $usernames);

        foreach($availableUsers as $user) {
            $userChoices[$user] = $user;
        }

        return $this->app['form.factory']->createBuilder(FormType::class, $data)
            ->add('groupname', null, array(
                'label' => 'Group Name',
                'required' => true
            ))
            ->add('users', ChoiceType::class, array(
                'choices' => $userChoices,
                'expanded' => true,
                'multiple' => true
            ))
            ->getForm();
    }

    public function delete(Request $request, $groupname)
    {
        $group = $this->htService->findGroup($groupname);
        if (null === $group) {
            throw new \Exception('Group not found', 404);
        }

        $this->htService->removeGroup($group)->write();
        $this->app['session']->getFlashBag()->add('success', "Group {$groupname} deleted successfuly.");

        return $this->app->redirect('/');
    }

}
