<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductAdminController extends AbstractController
{
    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/product_admin/index.html.twig', [
            'products' => $em->getRepository(Product::class)->findAll(),
        ]);
    }

    #[Route('/admin/product/create', name: 'app_admin_product_add')]
    #[Route('/admin/product/{id}/edit', name: 'app_admin_product_edit')]
    public function create(Product $product = null, EntityManagerInterface $em, Request $request): Response
    {
        // Si le produit n'existe pas, on en crée un nouveau
        if(!$product) {
            $product = new Product();
            $new = true;
        }

        // Création du formulaire
        $form = $this->createForm(ProductFormType::class, $product);

        // Récupération des données du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            // Création d'un message flash
            $this->addFlash("success", ($new ?? false) ? "Le produit a bien été ajouté" : "Le produit a bien été modifié");

            // Redirection vers la liste des produits
            return $this->redirectToRoute('app_admin_product');
        }

        // Affichage du formulaire
        return $this->render('admin/product_admin/create.html.twig', [
            'productForm' => $form->createView(),
            'new' => $new ?? false,
        ]);
    }

    #[Route('/admin/product/{id}/delete', name: 'app_admin_product_delete')]
    public function delete(EntityManagerInterface $em, Product $product): Response
    {
        $em->remove($product);
        $em->flush();

        // Création d'un message flash
        $this->addFlash("success", "Le produit a bien été supprimé");

        // Redirection vers la liste des produits
        return $this->redirectToRoute('app_admin_product');
    }
}
