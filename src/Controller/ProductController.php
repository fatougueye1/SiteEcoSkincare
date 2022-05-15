<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }


    #[Route('/produit/{Slug}', name: 'product')]

    public function show($Slug): Response
    {

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['Slug' => $Slug]);

        if (!$product) {
            return $this->redirectToRoute( 'products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
