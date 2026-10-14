<?php
 
namespace App\Controller;
 
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
 
class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
 
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
 
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload image: '.$e->getMessage());
                    return $this->redirectToRoute('app_product');
                }
 
                $product->setImage($newFilename);
            }
 
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Product added');

            return $this->redirectToRoute('app_product');
        }
 
        return $this->render('product/index.html.twig', [
            'form' => $form,
            'products' => $products,
        ]);
    }
}