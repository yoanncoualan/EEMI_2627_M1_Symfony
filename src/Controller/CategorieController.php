<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category added');

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form' => $form,
        ]);
    }

    #[Route('/categorie/{id}', name: 'app_categorie_show')]
    public function show(Category $category): Response
    {
        return $this->render('categorie/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Category $category)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('csrf'))) {
            $em->remove($category);
            $em->flush();

            $this->addFlash('warning', 'Category deleted');
        }
        return $this->redirectToRoute('app_categorie');
    }
}
