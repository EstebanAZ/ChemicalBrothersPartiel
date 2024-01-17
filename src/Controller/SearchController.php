<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProductSearchFormType;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;


class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $searchPerformed = false;
        $form = $this->createForm(ProductSearchFormType::class);
        $form->handleRequest($request);
        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $keyword = $form->getData()['keyword'];
            $products = $productRepository->findByKeyword($keyword);
            $searchPerformed = true;
        }

        // Retournez la même vue mais avec les produits trouvés et la barre de recherche
        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'searchPerformed' => $searchPerformed
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function homepage(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductSearchFormType::class);
        $form->handleRequest($request);
        $products = [];
        $searchPerformed = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $keyword = $form->getData()['keyword'];
            $products = $productRepository->findByKeyword($keyword);
            $searchPerformed = true;
            // Redirigez vers la même page avec les résultats de la recherche
            //return new RedirectResponse($this->generateUrl('app_home', ['products' => $products]));
        }

        // Si vous avez d'autres fonctionnalités pour la page d'accueil, vous pouvez les gérer ici

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'searchPerformed' => $searchPerformed
        ]);
    }
}
