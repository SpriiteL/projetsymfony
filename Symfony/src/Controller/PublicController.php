<?php

namespace App\Controller;

use App\Form\SellingFormType;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PublicController extends AbstractController
{
    #[Route('/', name: 'app_public')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('public/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/shop', name: 'app_shop')]
    public function shop(): Response
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/page', name: 'app_page')]
    public function page(): Response
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('public/contact.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/detail_product/{id}', name: 'app_detail')]
    public function detailProduct(Environment $twig, Product $product): Response
    {
        return new Response($twig->render('public/detail.html.twig', [
            'product' => $product,
    ]));
    }

    // #[Route('/sell', name: 'app_sell')]
    // public function sell(Request $request): Response
    // {
    //     $product = new Product(); 
    //     $form = $this->createForm(SellingFormType::class, $product);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //     }

    //     return $this->render('sell.html.twig', [
    //         'sellingform' => $form->createView(),
    //     ]);
    // }

    #[Route('/sell', name: 'app_sell')]
    public function sell(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(SellingFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérez le téléchargement de l'image ici (enregistrez le fichier sur le serveur si nécessaire)

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été ajouté avec succès.');

            return $this->redirectToRoute('product_index'); // Redirigez vers la liste des produits
        }

        return $this->render('sell.html.twig', [
            'sellingform' => $form->createView(),
        ]);
    }
}

