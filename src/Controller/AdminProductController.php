<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Categorie;
use App\Form\ProductType;
use App\Form\CategorieType;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProductController extends AbstractController
{
    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        

        if (!$products) {
            throw $this->createNotFoundException(
                'Faut recréer de la fake data !!!'
            );
        }
        return $this->render('admin_product/index.html.twig', [
            'products' => $products,
            'controller_name' => 'AdminProductController'
        ]);
    }

    #[Route('/admin/product/add/{slug}', name: 'app_admin_product_add', requirements: ['slug' => '[a-z]+'])]
    #[Entity('product', options: ['slug' => 'slug'])]
    public function addProduct(Request $request, ProductRepository $productRepository, ?Product $product): Response
    {
        if(!$product){
            $product = new Product();
        }
        
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // $product = $form->getData();
            if(!$product->getCreatedAt()){
                $product->setCreatedAt(new DateTimeImmutable());
            }
            $product->setModifiedAt(new DateTimeImmutable());
            $productRepository->save($product, true);
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_admin_product');
        }
        return $this->render('admin_product/addProduct.html.twig', [
            'controller_name' => 'AdminProductController',
            'form' => $form
        ]);
    }

    // #[Route('product/{slug}', name: 'app_product_detail', requirements: ['slug' => '[a-z]+'])]
    // #[Entity('product', options: ['slug' => 'slug'])]
    // public function productDetail(Product $product): Response
    // {
        
    //     return $this->render('front/pages/productDetail.html.twig', [
    //         'product' => $product
    //     ]);
    // }
    // #[Route('/admin/product/edit/{slug}', name: 'app_admin_product_edit')]
    // #[Entity('product', options: ['slug' => 'slug'])]
    // public function editProduct(Product $product, Request $request, CategorieRepository $categorieRepository): Response
    // {
    //     $form = $this->createForm(ProductType::class, $product);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
            
    //         $categorie = $form->getData();
            
    //         try{
    //             $categorieRepository->save($categorie, true);
    //         }catch(\Exception $e){
    //             echo $e->getMessage();
    //             die();
    //         }
            
    //         // ... perform some action, such as saving the task to the database

    //         return $this->redirectToRoute('app_admin_product');
    //     }
    //     return $this->render('admin_product/addCategorie.html.twig', [
    //         'controller_name' => 'AdminProductController',
    //         'form' => $form
    //     ]);
    // }
}
