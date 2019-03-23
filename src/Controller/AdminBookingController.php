<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class AdminBookingController extends AbstractController
{
    /**
     * permet d'afficher la liste des réservations
     * 
     * @Route("/admin/bookings", name="admin_booking_index")
     *
     * @param BookingRepository $repo
     * @return Response
     */
    public function index(BookingRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Booking::class);
        //comments = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }
    

    /**
     * Permet d'éditer une réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request,ObjectManager $manager) {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation numéro {$booking->getId()} a bien été modifiée !"
            );
            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig',[
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }


    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @param Booking $booking
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager) {
        $manager->remove($booking);
        $manager->flush();

    $this->addFlash(
        'success',
        "La réservation a bien été supprimée!"
    );
    return $this->redirectToRoute("admin_booking_index");
    }
}
