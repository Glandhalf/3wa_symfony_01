<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Product;
use App\Entity\Categorie;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(ProductRepository $productRepository): Response
    {
        $date = new \DateTime();
        
        $products = $productRepository->findAll();

        if (!$products) {
            throw $this->createNotFoundException(
                'Faut créer des produits en premier !!!'
            );
        }

        dump($products);

        return $this->render('front/index.html.twig', [
            'date' => $date,
            'products' => $products
        ]);
    }

    #[Route('/categories', name: 'app_categories_list')]
    public function categoriesList(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'Faut créer des catégories en premier !!!'
            );
        }

        dump($categories);

        return $this->render('front/pages/categoriesList.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('product/{slug}', name: 'app_product_detail', requirements: ['slug' => '[a-z]+'])]
    #[Entity('product', options: ['slug' => 'slug'])]
    public function productDetail(Product $product): Response
    {
        
        return $this->render('front/pages/productDetail.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('categorie/{slug}', name: 'app_categorie_detail', requirements: ['slug' => '[a-z]+'])]
    public function categorieDetail(Categorie $categorie): Response
    {
        return $this->render('front/pages/categorieDetail.html.twig', [
            'categorie' => $categorie
        ]);
    }
    

    #[Route('pages/{page}', name: 'app_static_page', requirements: ['page' => '[a-z]+'])]
    public function staticPage(string $page, Environment $twig): Response
    {
        $template = 'front/pages/'. $page .'.html.twig';
        $loader = $twig->getLoader();

        if(!$loader->exists($template)){
            throw new NotFoundHttpException();
        }
        return $this->render($template, []);
    }
}
