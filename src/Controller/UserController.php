<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/index", name="user_index", methods="GET")
     */
    public function index(): Response
    {
        $usuarios = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('user/index.html.twig', ['users' => $usuarios]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {

        $cantidad_usuarios_administradores = $this->getDoctrine()
            ->getRepository(User::class)
            ->getCantidadUsuariosAdministradores();

        if($cantidad_usuarios_administradores>0){
            if (false === $authChecker->isGranted('ROLE_ADMINISTRADOR')) {
                throw new AccessDeniedException('Access Denied.');
            }
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="user_show", methods="GET")
     */
    public function show(User $user, AuthorizationCheckerInterface $authChecker, Security $security): Response
    {
        if (false === $authChecker->isGranted('ROLE_ADMINISTRADOR')) {
                $usuario_autenticado = $security->getUser();
                if($usuario_autenticado->getId() != $user->getId()){
                    throw new AccessDeniedException('Access Denied.');
                }                
        }
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, AuthorizationCheckerInterface $authChecker): Response
    {
        if (false === $authChecker->isGranted('ROLE_ADMINISTRADOR')) {
                throw new AccessDeniedException('Access Denied.');
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user, AuthorizationCheckerInterface $authChecker, Security $security): Response
    {
        if (false === $authChecker->isGranted('ROLE_ADMINISTRADOR')) {
                throw new AccessDeniedException('Access Denied.');
        }else{
            $usuario_autenticado = $security->getUser();
                if($usuario_autenticado->getId() == $user->getId()){
                   throw new AccessDeniedException('This is a current autenticated user.'); 
                } 
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
