<?php

namespace App\Controller;

use App\Form\ProductSearchFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListClientController extends AbstractController
{
    #[Route('/products', name: 'app_product_list_client')]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $form = $this->createForm(ProductSearchFormType::class);
        $form->handleRequest($request);

        $user = $this->getUser();

        $search = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('keyword')->getData();
        }

        // Retrieve products for the current user along with associated FDS
        $products = $productRepository->findProductsForUser($user, $search);

        return $this->render('product_list_client/index.html.twig', [
            'products' => $products,
            'formSearch' => $form->createView(),
            'search' => $search,
        ]);
    }
}
