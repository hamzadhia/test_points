<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FilterGroupeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin', name: 'admin_user_')]
class UserController extends AbstractController
{

    #[Route('', name: 'index')]
    #[Route('/{sort}/{order}', name: 'sort', requirements: ['sort' => 'name|groupe|createdAt'])]
    public function index(Request $request, UserRepository $user_repository, $sort = null, $order = null): Response
    {
        $order_list = [
            'DESC' => 'ASC',
            'ASC' => 'DESC'
        ];
        $new_order = $order ? $order_list[$order] : 'DESC';
        $filter = $this->createForm(FilterGroupeType::class);
        $filter->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $groupe = $filter->get('groupe')->getData();
            $groupe_data = $groupe ? $groupe->value : null;
            return $this->render('admin/users/index.html.twig', [
                'users' => $user_repository->findUserByRole('ROLE_USER', $sort, $order, $groupe_data),
                'newOrder' => $new_order,
                'filter' => $filter
            ]);
        }
        return $this->render('admin/users/index.html.twig', [
            'users' => $user_repository->findUserByRole('ROLE_USER', $sort, $order),
            'newOrder' => $new_order,
            'filter' => $filter
        ]);
    }

    #[Route('/add-user', name: 'add')]
    public function addUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/users/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-user/{id}', name: 'edit')]
    public function editUser(Request $request, User $user, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            if ($password) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );
                $user->setPassword($hashedPassword);
            }
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/users/add.html.twig', [
            'form' => $form,
        ]);
    }
}
