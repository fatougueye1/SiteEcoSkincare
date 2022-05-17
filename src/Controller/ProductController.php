<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]

    public function index(Request $request, ProductRepository $productRepository): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findWithSearch($search);
        }
        else {
            $products = $productRepository->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
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
