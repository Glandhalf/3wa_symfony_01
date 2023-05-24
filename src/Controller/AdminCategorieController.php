<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'app_admin_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        if (!$categories) {
            throw $this->createNotFoundException(
                'Faut recréer de la fake data !!!'
            );
        }
        
        return $this->render('admin_categorie/index.html.twig', [
            'controller_name' => 'AdminCategorieController',
            'categories' => $categories
        ]);
    }

    #[Route('/admin/categorie/add/{slug}', name: 'app_admin_categorie_add')]
    #[Entity('categorie', options: ['slug' => 'slug'])]
    public function addCategorie(Request $request, CategorieRepository $categorieRepository, ?Categorie $categorie): Response
    {
        if(!$categorie){
            $categorie = new Categorie();
        }
        
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // $categorie = $form->getData();

            $categorieRepository->save($categorie, true);
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_admin_categorie');
        }
        return $this->render('admin_categorie/addCategorie.html.twig', [
            'controller_name' => 'AdminCategorieController',
            'form' => $form
        ]);
    }
}
