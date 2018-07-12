<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccesoController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        $cantidad_usuarios_administradores = $this->getDoctrine()
            ->getRepository(User::class)
            ->getCantidadUsuariosAdministradores();
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        if ($cantidad_usuarios_administradores==0){
            return $this->redirectToRoute('user_new');
        }

        return $this->render('acceso/login.html.twig', array(
            'error' => $error,
            'lastUsername' => $lastUsername
        ));
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
