<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Token;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class TokenController extends AbstractController
{
    /**
     * @Route("/token", name="token")
     */
    public function index(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$request->isXMLHttpRequest())
            return new Response('Cannot use a method without an AJAX call.');

        $token = new Token();
        $token->setToken(bin2hex(random_bytes(60)));
        $entityManager = $doctrine->getManager();
        $entityManager->persist($token);
        $entityManager->flush();

        $result = json_encode($token->getToken());
        $response = new JsonResponse();
        $response->setContent($result);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
