<?php

namespace App\Controller;


use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home" , methods="GET")
     */
    public function index(PinRepository $pinRepository) :Response
    {
        $pins = $pinRepository->findby ( [] , ['createAt' => 'DESC' ]);

        return $this->render('pins/index.html.twig' , compact('pins'));
    }


    /**
     * @Route("/pins/create", name="app_create" , methods="GET|POST")
     */
    public function create(Request $request , EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        $form =$this->createForm(PinType::class, $pin);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($pin);
            $em->flush();
            $this->addFlash('success', 'Dépôt de projet réussi !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig' , [
            'form' => $form ->createView()

        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show (Pin $pin ) : Response
    {
        return $this->render('pins/show.html.twig',compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_edit" , methods="GET|PUT")
     */
    public function edit(Request $request , EntityManagerInterface $em ,Pin $pin): Response
    {
        $form =$this->createForm(PinType::class, $pin ,[
            'method' =>'PUT'
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'Modification de projet effectuée !');


            return $this->redirectToRoute('app_home');
        }


        return $this->render('pins/edit.html.twig',[
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_delete" , methods="DELETE")
     */
    public function delete(Request $request , Pin $pin , EntityManagerInterface $em ): Response
    {
        if ($this->isCsrfTokenValid('pin_deletion' . $pin->getId(), $request->request->
        get('csf-token-security')));

            $em->remove($pin);
            $em->flush();

        $this->addFlash('info', ' La suppression de projet réussi !');


        return $this->redirectToRoute('app_home');

    }
}