<?php

namespace App\Controller\admin;

use App\Entity\Fds;
use App\Entity\Notification;
use App\Form\FdsAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FdsAdminController extends AbstractController
{
    #[Route('/admin/fds', name: 'app_admin_fds')]
    public function index(EntityManagerInterface $em): Response
    {
        $UptodateFds = $em->getRepository(Fds::class)->createQueryBuilder('f')
            ->where('f.id NOT IN (
                SELECT IDENTITY(fp.parent)
                FROM App\Entity\Fds fp
                WHERE fp.parent IS NOT NULL
            )')
            ->getQuery()
            ->getResult();

        return $this->render('admin/fds_admin/index.html.twig', [
            'fdsList' => $UptodateFds,
        ]);
    }

    #[Route('/admin/fds/create', name: 'app_admin_fds_create')]
    #[Route('/admin/fds/{id}/edit', name: 'app_admin_fds_edit')]
    public function create(Fds $fds = null , Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if(!$fds){
            $fds = new Fds();
            $new = true;
        }

        $form = $this->createForm(FdsAdminType::class, $fds, [
            'is_edit' => isset($new) ? false : true ,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $fds = $form->getData();

            if(!isset($new)){
                $newFds = new Fds();
                $newFds->setParent($fds);
                $newFds->setProduct($fds->getProduct());
                $newFds->setCreatedAt(new \DateTimeImmutable());

                // todo : enregistrement du fichier de la FDS dans un répertoire sécurisée
                $newFds->setPath('');
                $em->persist($newFds);
                
                $fds->setProduct(null);
                $em->persist($fds);

                $em->flush();
                
                // Parcours des utilisateur liés au produit
                foreach($newFds->getProduct()->getUsers() as $user){

                    // Création d'une notification
                    $notification = new Notification();
                    $notification->setClient($user);
                    $notification->setCreatedAt(new \DateTimeImmutable());
                    $notification->setMessage('La fiche de sécurité du produit '.$newFds->getProduct()->getName().' a été modifiée. Vous pouvez la consulter en cliquant sur le lien ci-dessous. <br> <a href="'.$this->generateUrl('app_admin_fds_download', ['id' => $newFds->getId()]).'">Télécharger la fiche de sécurité</a>');
                    $em->persist($notification);

                    // Envoi d'un mail
                    $mail = new TemplatedEmail();
                    $mail->to($user->getEmail())
                        ->from('noreply@chemicalbrothers.com')
                        ->subject('Modification de la fiche de sécurité du produit '.$newFds->getProduct()->getName())
                        ->htmlTemplate('emails/notification.html.twig')
                        ->context([
                            'fds' => $newFds,
                            'user' => $user,
                        ]);
                    $mailer->send($mail);
                }
            }
            else{
                // création d'une nouvelle fds
                $fds->setCreatedAt(new \DateTimeImmutable());
                $fds->setPath('');
                $em->persist($fds);

                $em->flush();
            }

            // Ajout d'un message flash
            $this->addFlash('success', $new ?? false ? 'La fiche de sécurité a été créée avec succès' : 'La fiche de sécurité a été modifiée avec succès');

            return $this->redirectToRoute('app_admin_fds');
        }

        return $this->render('admin/fds_admin/create.html.twig', [
            'fdsForm' => $form->createView(),   
            'new' => $new ?? false,
        ]);
    }


    #[Route('/admin/fds/{id}/download', name: 'app_admin_fds_download')]
    public function download(Fds $fds): Response
    {
        // todo : vérifier que l'utilisateur a le droit de télécharger la FDS
        $path = $this->getParameter('fds_directory').'/'.$fds->getPath();

        return $this->file($path);
    }

    #[Route('/admin/fds/{id}/history', name: 'app_admin_fds_hystory')]
    public function history(Fds $fds): Response
    {
        return $this->render('admin/fds_admin/history.html.twig', [
            'fds' => $fds,
        ]);
    }

}
