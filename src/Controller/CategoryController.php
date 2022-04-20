<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name:'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository):Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/{categoryName}', methods:['GET'],  name: 'show')]
    public function show(string $categoryName,CategoryRepository $categoryRepository, programRepository $programRepository):Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) 
        {
            throw $this->createNotFoundException(
                'Pas de catégories de trouvées avec ce nom'.$categoryName.'.',
            );

        }
        $id = $category->getId();
        $listPrograms = $programRepository->findBy(['category' =>$id], ['id'=>'DESC'],3);
        
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'listPrograms' => $listPrograms,
        ]);
    }
}