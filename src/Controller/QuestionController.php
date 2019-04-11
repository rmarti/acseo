<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionListe;
use App\Form\QuestionAddType;
use App\Form\QuestionListeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class QuestionController extends AbstractController
{
    public function add(Request $request, LoggerInterface $logger)
    {
        $question = new Question();
        $form   = $this->createForm(QuestionAddType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $logger->debug('formulaire soumis');

            $question->setStatus("open");

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre question bien enregistrée.'
            );

            $logger->debug('fin traitement formulaire ajout');
        }


        return $this->render('question/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function liste(Request $request, LoggerInterface $logger)
    {



        $listeQuestion= new QuestionListe();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Question::class);
        $questions =  $repository->findAll();

        foreach ($questions as $key => $quest) {
          $listeQuestion->getQuestions()->add($quest);
        }

        $form  = $this->createForm(QuestionListeType::class, $listeQuestion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();


            $this->addFlash(
                'success',
                'Changement de status ok'
            );

            $logger->debug('fin traitement formulaire maj');
        }


        return $this->render('question/liste.html.twig', [
            'form' => $form->createView(),
            "liste" =>$listeQuestion->getQuestions()
        ]);
    }




}
