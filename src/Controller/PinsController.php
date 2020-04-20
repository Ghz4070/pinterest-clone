<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $repo): Response
    {
        return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]);
    }


    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $pin = new Pin;

        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class, [
                'attr' => ['autofocus' => true]
            ])
            ->add('description', TextareaType::class, ['attr' => ['rows' => 5, 'cols' => 50]])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// -----------------

    /* use Doctrine\ORM\EntityManagerInterface;
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Pin::class);

        $pins = $repo->findAll();

        return $this->render('pins/index.html.twig', compact(
            'pins'
        ));
    }*/

//    -----------------------

    /*public function create(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if ($this->isCsrfTokenValid('pins_create', $data['_token'])) {
                $pin = new Pin;
                $pin->setTitle($data['title']);
                $pin->setDescription($data['description']);

                $this->em->persist($pin);
                $this->em->flush();
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig');
    }*/

//---------------------
//    public function index(): Response
//    {
//        $repoTestUn = $this->em->getRepository('App\Entity\Pin');
//
//        // ou cette syntax est mieux
//
//        $repo = $this->em->getRepository(Pin::class);
//
//        $pins = $repo->findAll();
//
//        // $pin = new Pin();
//        // $pin->setTitle('Title 1');
//        // $pin->setDescription('Description 1');
//
//        // $this->em->persist($pin); // permet d'informer l'entity manager qu'on veux perister notre objet
//        // $this->em->flush(); // permet de valider la data et d'envoyé les données persisté
//
//        // dump($pin); // similaire du var_dump()
//        // die();
//
//        // dd($pin); // dump() + die()
//
//        return $this->render('pins/index.html.twig', compact(
//            'pins'
//        ));
//    }
// -----------------------
//    injecter directement le repository pour recuperer tous de la bdd
//    use App\Repository\PinRepository;
//    public function index(PinRepository $repo): Response
//    {
//        return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]);
//    }

}
