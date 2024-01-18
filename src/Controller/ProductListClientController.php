<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListClientController extends AbstractController
{
    #[Route('/products', name: 'app_product_list_client')]
    public function index(ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        
        // Retrieve products for the current user along with associated FDS
        $products = $productRepository->findProductsForUser($user);

        return $this->render('product_list_client/index.html.twig', [
            'products' => $products,
        ]);
    }
}
