<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Infos;
use Login\LoginBundle\modals\Login;
use Login\LoginBundle\Entity\Identifiant;

class DefaultController extends Controller {

    public function indexAction(Request $request) {


        $session = $this->getRequest()->getSession();
        if ($request->getMethod() == 'POST') {
            $session->clear();
            $username = $request->get('username');
            $password = $request->get('password');
            $remenber = $request->get('remenber');
            $em = $this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository('LoginLoginBundle:Infos');



            $user = $repository->findOneBy(array('username' => $username, 'password' => $password));
            if ($user) {
                $identifiant = $this->getDoctrine()->getRepository("LoginLoginBundle:Identifiant")->findAll();
                return $this->render('LoginLoginBundle:Default:listContact.html.twig', array('identifiant' => $identifiant));
            }
            return $this->render('LoginLoginBundle:Default:login.html.twig', array('name' => 'erreur de login'));
        }
        return $this->render('LoginLoginBundle:Default:login.html.twig');
    }

    public function signupAction(Request $request) {

        if ($request->getMethod() == 'POST') {
            $username = $request->get('username');
            $password = $request->get('password');
            $nom = $request->get('nom');
            $email = $request->get('email');
            $telephone = $request->get('telephone');


            $user = new Infos();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setNom($nom);
            $user->setEmail($email);
            $user->setTelephone($telephone);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();
            if ($user) {
                return $this->render('LoginLoginBundle:Default:login.html.twig');
            }
        }
        return $this->render('LoginLoginBundle:Default:signup.html.twig');
    }

    public function newContactAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $email = $request->get('email');
            $telephone = $request->get('telephone');

            $iden = new Identifiant();
            $iden->setNom($nom);
            $iden->setPrenom($prenom);
            $iden->setEmail($email);
            $iden->setTelephone($telephone);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($iden);
            $em->flush();
            $identifiant = $this->getDoctrine()->getRepository("LoginLoginBundle:Identifiant")->findAll();
            if ($identifiant) {

                return $this->render('LoginLoginBundle:Default:listContact.html.twig', array('identifiant' => $identifiant));
            }
        }
        return $this->render('LoginLoginBundle:Default:newContact.html.twig');
    }

    public function listContactAction() {

        $identifiant = $this->getDoctrine()->getRepository("LoginLoginBundle:Identifiant")->findAll();
        return $this->render('LoginLoginBundle:Default:listContact.html.twig', array('identifiant' => $identifiant));
    }

    public function supprimerAction(Identifiant $identifiant) {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($identifiant);
        $em->flush();
        $identifiant = $this->getDoctrine()->getRepository("LoginLoginBundle:Identifiant")->findAll();

        $this->render('LoginLoginBundle:Default:listContact.html.twig', array('identifiant' => $identifiant));
        return $this->render('LoginLoginBundle:Default:listContact.html.twig', array('identifiant' => $identifiant));
    }

   
}
