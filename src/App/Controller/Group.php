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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;

/**
 * Class Group Controller
 * @category Controller
 * @author   Rafael Goulart <rafaelgou@gmail.com>
 */
class Group extends AbstractController
{
    /**
     * Add a record
     * @param Request $request The HTTP Request
     * @return Response
     */
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

    /**
     * Edit a record
     * @param Request $request   The HTTP Request
     * @param string  $groupname Group name
     * @return Response
     */
    public function edit(Request $request, $groupname)
    {
        $group = $this->htService->findGroup($groupname);
        if (null === $group) {
            throw new \Exception('Group not found', 404);
        }

        $form = $this->getForm($group);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $group = $form->getData();
                $group->setName($groupname);
                $this->htService->persist($group)->write();
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

    /**
     * Delete a record
     * @param Request $request   The HTTP Request
     * @param string  $groupname Group name
     * @return Response
     */
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

    /**
     * Get Form instance
     * @param array $data Form initial data
     * @return Form
     */
    protected function getForm($data = array())
    {
        $usernames = $this->htService->getUsernames();
        $userChoices = array_combine($usernames, $usernames);

        return $this->app['form.factory']->createBuilder(FormType::class, $data)
            ->add('name', null, array(
                'label' => 'Group Name',
                'required' => true,
            ))
            ->add('users', ChoiceType::class, array(
                'choices' => $userChoices,
                'expanded' => true,
                'multiple' => true,
            ))
            ->getForm();
    }
}
