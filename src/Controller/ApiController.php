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
     * @Route("/api/ad/display/{id}", name="api_ad")
     */
    public function displayAd(ManagerRegistry $doctrine, $id): JsonResponse
    {
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
     * @Route("/api/ad/add", methods={"POST"}, name="api_add")
     */
    public function addAd(Request $request, ManagerRegistry $doctrine): Response
    {
        $name = $request->get('name');
        $price = $request->get('price');
        $description = $request->get('description');

        $ad = new Ad();
        $ad->setName($name);
        $ad->setPrice($price);
        $ad->setDescription($description);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($ad);
        $entityManager->flush();

        $id = $ad->getId();

        $response = new Response('Ad created.', 201, [
            'Location' => '/api/ad/'.$id
        ]);

        return $response;
    }
}
