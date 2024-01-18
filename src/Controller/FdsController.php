<?php

namespace App\Controller;

use App\Entity\Fds;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FdsController extends AbstractController
{
    #[Route('/fds/{id}', name: 'app_fds_view')]
    public function index(Fds $fds, Security $security, EntityManagerInterface $em)
    {
        // chemin d'accès au dossier des FDS
        $filename = $fds->getPath();
        $filePath = $this->getParameter('fds_directory').'/'.$filename;

        // si le fichier n'existe pas
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Le fichier n\'existe pas');
        }
        
        // Vérification des droits de l'utilisateur pour l'accès au fichier
        $user = $security->getUser();
        $product = $fds->getProduct();

        if($em->getRepository(Fds::class)->isFdsAccessible($user, $product)){
            return new Response(file_get_contents($filePath), 200, [
                'Content-Type' => 'application/pdf'
            ]);
        }else{
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour accéder à ce fichier');
        }
    }
}
