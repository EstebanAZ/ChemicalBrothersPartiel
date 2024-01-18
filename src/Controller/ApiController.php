<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    // créer un eroute api pour marquer une notification comme étant lue
    /**
     * @Route("/api/notification/{id}/read", name="api_notification_read", methods={"POST"})
     */
    public function readNotification(Notification $notification, EntityManagerInterface $em): Response
    {
        // si la notification n'existe pas
        if (!$notification) {
            // on retourne une erreur
            return $this->json([
                'status' => 404,
                'message' => 'Notification incorrecte'
            ], 404);
        }

        // si la notification existe
        // on la marque comme étant lue
        $notification->setIsRead(true);

        // on enregistre les modifications
        $em->flush();

        // on retourne une réponse
        return $this->json([
            'status' => 200,
            'message' => 'Marqué comme lu'
        ], 200);
    }
}
