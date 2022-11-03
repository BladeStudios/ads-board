<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AdFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\SearchFormType;

class AdController extends AbstractController
{
    /**
     * @Route("/ad/add", name="ad_add")
     */
    public function addAd(Request $request, ManagerRegistry $doctrine): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdFormType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($ad);
            $entityManager->flush();
            $this->addFlash('success', 'Pomyślnie dodano ogłoszenie!');
            return $this->redirectToRoute('ad_add');
        }
        return $this->render('ad/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ad/display/{id}", name="ad_display")
     */
    public function displayAd(ManagerRegistry $doctrine, $id): Response
    {
        $ad = $doctrine->getRepository(Ad::class)->find($id);
        if(!$ad) $this->addFlash('error', 'Ogłoszenie o id '.$id.' nie istnieje!');

        return $this->render('ad/display.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * @Route("/ad/search", name="ad_search")
     */
    public function searchAd(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        $ads = null;
        $criterias = null;
        if(count($request->query->all())>0)
        {
            $name = $request->get('name');
            $min_price = $request->get('min_price');
            $max_price = $request->get('max_price');
            $description = $request->get('description');
            $ads = $doctrine->getRepository(Ad::class)->search($name, $min_price, $max_price, $description);
            $criterias = 1;
        }

        return $this->render('ad/search.html.twig', [
            'criterias' => $criterias,
            'ads' => $ads,
            'form' => $form->createView()
        ]);
    }
}
