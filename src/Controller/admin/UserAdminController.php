<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserAdminController extends AbstractController
{
    #[Route('/admin/user', name: 'app_admin_user')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/user_admin/index.html.twig', [
            'users' => $em->getRepository(User::class)->findAll(),
        ]);
    }

    #[Route('/admin/user/{id}', name: 'app_admin_user_show')]
    public function show(User $user): Response
    {
        return $this->render('admin/user_admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/user/{id}/edit', name: 'app_admin_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {   
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //mise à jour de l'utilisateur
            $em->flush();

            //message flash de confirmation
            $this->addFlash('success', 'Utilisateur modifié avec succès');

            //redirection vers la liste des utilisateurs
            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/user_admin/edit.html.twig', [
            'user' => $user,
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'app_admin_user_delete')]
    public function delete(User $user, EntityManagerInterface $em, TokenInterface $token): Response
    {
        if($token->getUser() === $user) {
            //message flash d'erreur'
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte');

            //redirection vers la liste des utilisateurs
            return $this->redirectToRoute('app_admin_user');
        }

        //suppression de l'utilisateur
        $em->remove($user);
        $em->flush();

        //message flash de confirmation
        $this->addFlash('success', 'Utilisateur supprimé avec succès');

        //redirection vers la liste des utilisateurs
        return $this->redirectToRoute('app_admin_user');
    }
}
