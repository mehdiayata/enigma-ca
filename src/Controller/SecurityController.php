<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \Date;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class SecurityController extends AbstractController
{
     /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse {
        // Récupère les données du parametre request fourni en JSON et les décodes
        $data = json_decode($request->getContent(), true);

       // Appel de l'entité user
        $user = new User();
        
        // Renseigne l'entité User avec les informations envoyé dans le data        
        $em = $this->getDoctrine()->getManager();
        $user->setEmail($data["email"]);

        // Enregistre le mot de passe en encodant ce dernier
        $user->setPassword($passwordEncoder->encodePassword($user, $data["password"]));

        // Envoie en BDD
        $em->persist($user);
        $em->flush();

        // Return une réponse en affichant l'identifiant et le mot de passe
        return new JsonResponse ([
            'message' => "Votre compte est créer, vos identifiants sont à garder",
            'email' => $data["email"],
            'password' => $data["password"]
        ]);
    }
    
    /**
     * @Route(name="api_login", path="/api/login_check ")
     * @return JsonResponse
     */
    public function login() : JsonResponse
    {
        $user = new User();

        /* Retourne la JWT si les informations entrées (l'email et le mot de passe) sont correct
           la configuration permettant de vérifier si ces informations sont valide se trouve dans le fichier 
           config/security.yaml
        */
        return new Response([
            'email' => $user->getEmail(),
            'password' => $user->getPassword()
        ]);
        
    }

    /**
     * @Route("/getPublicKey", name="getPublicKey", methods={"GET"})
     */
    public function getPublicKey(){
        // Retourne le contenu de la clé publique dans le fichier /congif/jwt/public.pem
        return new Response(file_get_contents('C:\wamp64\www\enigmaCA2\config\jwt\public.pem'));
    }
 
    
}
