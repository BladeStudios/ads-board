<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Ad;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Token;


class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig');
    }

    /**
     * @Route("/api/ad/display/{id}", name="api_ad_display")
     */
    public function displayAd(Request $request, ManagerRegistry $doctrine, $id): JsonResponse
    {
        if(!$this->isTokenValid($request, $doctrine))
            return $this->json('Unauthorized', 401, ["Content-Type" => "application/json"]);

        $ad = $doctrine->getRepository(Ad::class)->find($id);
        if(!$ad)
        {
            throw new NotFoundHttpException('Not found');
        }

        $data = $this->entityToArray($ad);

        return $this->json($data, 200, ["Content-Type" => "application/json"]);
    }

    public function entityToArray($data)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return json_decode($serializer->serialize($data, 'json'));
    }

    /**
     * @Route("/api/ad/add", methods={"POST"}, name="api_ad_add")
     */
    public function addAd(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$this->isTokenValid($request, $doctrine))
            return $this->json('Unauthorized', 401, ["Content-Type" => "application/json"]);

        $name = $request->get('name');
        $price = $request->get('price');
        $description = $request->get('description');

        if(!$name || !$price || !$description)
            return $this->json('Bad Request', 400, ["Content-Type" => "application/json"]);

        $price_regex = "/^[1-9]{1}[0-9]*(\.[0-9]{2})?$/";
        if(preg_match($price_regex, $price)==false)
            return $this->json('Bad Request', 400, ["Content-Type" => "application/json"]);

        $ad = new Ad();
        $ad->setName($name);
        $ad->setPrice($price);
        $ad->setDescription($description);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($ad);
        $entityManager->flush();

        $id = $ad->getId();

        $response = new JsonResponse('Ad created.', 201, [
            'Location' => '/api/ad/'.$id
        ]);

        return $response;
    }

    /**
     * @Route("/api/ad/search", name="api_ad_search")
     */
    public function searchAd(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        if(!$this->isTokenValid($request, $doctrine))
            return $this->json('Unauthorized', 401, ["Content-Type" => "application/json"]);

        $name = $request->get('name');
        $min_price = $request->get('min_price');
        $max_price = $request->get('max_price');
        $description = $request->get('description');

        $ads = $doctrine->getRepository(Ad::class)->search($name, $min_price, $max_price, $description);

        $data = $this->entityToArray($ads);

        return $this->json($data, 200, ["Content-Type" => "application/json"]);
    }

    public function isTokenValid(Request $request, ManagerRegistry $doctrine)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7);
        $result = $doctrine->getRepository(Token::class)->findBy(['token' => $token]);
        if($result) return true;
        else return false;
    }
}
