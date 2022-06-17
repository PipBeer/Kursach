<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/appointment', name: 'app_appointment')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppointmentType::class, new Appointment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment = $form->getData();
            $appointment->setUser($this->getUser());

            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Successfully created an appointment!');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('appointment/index.html.twig', [
            'controller_name' => 'AppointmentController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/appointment/list', name: 'app_appointment_list')]
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointments = $entityManager->getRepository(Appointment::class)->findAll();

        //TODO:: DO LIKE THAT!!!
//        $user = $this->getUser();
//        $appointments = $entityManager->getRepository(Appointment::class)->findByUser($this->getUser());



        return $this->render('appointment/list.html.twig', [
            'controller_name' => 'AppointmentController',
            'appointments' => $appointments,
        ]);
    }


    #[Route('/appointment/delete', name: 'app_appointment_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);

        $entityManager->remove($appointment);
        $entityManager->flush();

        $this->addFlash('success', 'Successfully deleted an appointment!');

        return $this->redirectToRoute('app_appointment_list');
    }

    #[Route('/appointment/edit', name: 'app_appointment_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Successfully edited an appointment!');

            return $this->redirectToRoute('app_appointment_list');
        }

        return $this->render('appointment/index.html.twig', [
            'controller_name' => 'AppointmentController',
            'form' => $form->createView(),
        ]);
    }
}
