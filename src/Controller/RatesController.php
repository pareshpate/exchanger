<?php

namespace App\Controller;

use App\Entity\Rates;
use App\Form\RatesType;
use App\Repository\RatesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RateService;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/rates")
 */
class RatesController extends AbstractController
{
    /**
     * @Route("/", name="rates_index", methods={"GET"})
     */
    public function index(RatesRepository $ratesRepository): Response
    {
        return $this->render('rates/index.html.twig', [
            'rates' => $ratesRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/form", name="rates_refresh_form", methods={"GET","POST"})
     */
    public function rateRefreshForm(Request $request): Response
    {
        $rate = new Rates();
        $form = $this->createForm(RatesType::class, $rate);
        $form->remove('rate');
        $form->remove('created');
        $form->remove('updated');
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $currencyId = $form["currency"]->getData();
            $configuration = [
                'exchange_api_url' => $this->getParameter('exchange_api_url'),
                'exchange_base_currency' => $this->getParameter('exchange_base_currency')
            ];
            $rateService = new RateService($configuration);
            try {
                $convertedRate = $rateService->refreshRate($currencyId);
                $entityManager = $this->getDoctrine()->getManager();
                $rate->setRate($convertedRate);
                $rate->setCreated(new \DateTime());
                $rate->setUpdated(new \DateTime());
                $entityManager->persist($rate);
                $entityManager->flush();
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'error',
                    'Something went wrong while fetching the rates!'
                );
            }
            
            return $this->redirectToRoute('rates_index');
        }
        
        return $this->render('rates/rate_refresh_form.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/new", name="rates_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rate = new Rates();
        $form = $this->createForm(RatesType::class, $rate);
        $form->remove('created');
        $form->remove('updated');
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rate->setCreated(new \DateTime());
            $rate->setUpdated(new \DateTime());
            $entityManager->persist($rate);
            $entityManager->flush();
            
            $this->addFlash(
                'notice',
                'Your changes were added!'
            );
        
            return $this->redirectToRoute('rates_index');
        }
        
        return $this->render('rates/new.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rates_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rates $rate): Response
    {
        $form = $this->createForm(RatesType::class, $rate);
        $form->remove('created');
        $form->remove('updated');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rate->setUpdated(new \DateTime());
            $entityManager->flush();
            
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );
            
            return $this->redirectToRoute('rates_index');
        }

        return $this->render('rates/edit.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rates_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rates $rate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rate);
            $entityManager->flush();
            
            $this->addFlash(
                'notice',
                'Rate has been deleted!'
            );
        }

        return $this->redirectToRoute('rates_index');
    }
}
