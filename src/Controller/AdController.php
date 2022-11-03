<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AdFormType;
use Doctrine\Persistence\ManagerRegistry;

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
}
