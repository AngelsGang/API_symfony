<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


class UsersController extends FOSRestController
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    public function getUsersAction()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }
    public function getUserAction(User $user)
    {
        return $this->view($user);
    }
    /**
     * @Rest\Post("/users")
     * @paramConverter("user", converter="fos_rest.request_body")
     */
    public function postUsersAction(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    // "put_user" [PUT] /users/{id}
    public function putUserAction(Request $request, int $id)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $birthday = $request->get('birthday');

        $user = $this->userRepository->find($id);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setBirthday($birthday);
        $this->em->persist($user);
        $this->em->flush();

        return $this->view($user);
    }

    public function deleteUserAction(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
