<?php

namespace App\Controller;

use App\Form\SellingFormType;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Users;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/vendeur/{id}', name: 'app_vendeur')]
    public function vendeur(Environment $twig, Users $users, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['users' => $users]);

        return $this->render('public/vendeur.html.twig', [
            'users' => $users,
            'products' => $products,
        ]);
    }

    #[Route('/category{id}', name: 'app_category')]
    public function homme(Environment $twig, Category $category, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['category' => $category]);

        return $this->render('public/homme.html.twig', [
            'category' => $category,
            'products' => $products,
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

    

    #[Route('/sell', name: 'sell')]
    public function sell(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $formulaireVente = $this->createForm(SellingFormType::class, $product);

        $formulaireVente->handleRequest($request);

        if ($formulaireVente->isSubmitted() && $formulaireVente->isValid()) {
            $file = $formulaireVente['imagefile']->getData();
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/img/product';

            if (!is_dir($uploadDirectory)) {

                if (!mkdir($uploadDirectory, 0744, true)) {
                    return new Response('Impossible de créer le répertoire de destination');
                }
            }

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            $file->move($uploadDirectory, $newFilename);

            $product->setImagefile($newFilename);

            $em->persist($product);
            $em->flush();

            return new JsonResponse(['message' => 'Mise en vente realisee'], JsonResponse::HTTP_OK);
        }

        return $this->render('public/sell.html.twig', [
            'sellingform' => $formulaireVente->createView(),
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('public/profile.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
}