<?php

/**
 * (c) Adrien PIERRARD
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    /**
     * Show the Users list.
     *
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @Route("/users", name="user_list")
     */
    public function usersList(UserManager $userManager): Response
    {
        $users = $userManager->findAllUsers();

        return $this->render(
            'user/list.html.twig',
            ['users' => $users]
        );
    }

    /**
     * Add a User
     *
     * @param Request $request
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/users/create", name="user_create")
     */
    public function createUser(Request $request, UserManager $userManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->createUser($user);

            $this->addFlash(
                'success',
                "L'utilisateur a bien été ajouté."
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Update User information.
     *
     * @param User $user
     * @param Request $request
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editUser(User $user, Request $request, UserManager $userManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);

            $this->addFlash(
                'success',
                "L'utilisateur a bien été modifié"
            );

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            ['form' => $form->createView(), 'user' => $user]
        );
    }

    /**
     * Delete a user.
     *
     * @param User        $user
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/users/{id}/delete", name="user_delete")
     */
    public function delete(User $user, UserManager $userManager): Response
    {
        $userManager->deleteUser($user);

        $this->addFlash(
            'success',
            "L'utilisateur a bien été supprimé"
        );

        return $this->redirectToRoute('user_list');
    }
}
