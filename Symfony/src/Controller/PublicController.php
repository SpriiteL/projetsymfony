<?php

namespace App\Controller;

use App\Form\SellingFormType;
use App\Form\EditProfilType;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Users;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;
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
   
// Accueil
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
// Produit vendu par la personne
    #[Route('/vendeur/{id}', name: 'app_vendeur')]
    public function vendeur(Environment $twig, Users $users, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['users' => $users]);

        return $this->render('public/vendeur.html.twig', [
            'users' => $users,
            'products' => $products,
        ]);
    }

// Categories
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
// Contact
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            // Handle the form submission
        }

        return $this->render('contactcontent.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
// Detail produit
    #[Route('/detail_product/{id}', name: 'app_detail')]
    public function detail($id, ProductRepository $productRepository, FavorisRepository $favorisRepository)
    {
        $product = $productRepository->find($id);

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les produits favoris de l'utilisateur
        $favorisProducts = [];
        $favoris = [];
        if ($user) {
            $favoris = $favorisRepository->findBy(['users' => $user]);
            foreach ($favoris as $favori) {
                $favorisProducts[] = $favori->getProducts();
            }
        }

        // Passer les produits favoris à la vue
        return $this->render('public/detail.html.twig', [
            'product' => $product,
            'favoris' => $favoris,
            'favorisProducts' => $favorisProducts,
        ]);
    }

    
// vente des produits formulaire
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
// profile
    #[Route('/profile', name: 'app_profile')]
    public function profile(ProductRepository $productRepository): Response
    {
        return $this->render('public/profile.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/editprofile', name: 'app_edit_profile')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // handle the entities that were modified by the form (usually a flush is enough)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('public/editprofile.html.twig', [
            'editProfileForm' => $form->createView(),
        ]);
    }
// favoris
    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(EntityManagerInterface $em, FavorisRepository $favorisRepository): Response
    {
        $user = $this->getUser(); // get the current user
        $favoris = $favorisRepository->findBy(['users' => $user]);

        return $this->render('public/favoris.html.twig', ['favoris' => $favoris]);
    }

    #[Route('/favoris/ajouter/{productId}', name: 'app_favoris_ajouter')]
    public function ajouter($productId, EntityManagerInterface $em, ProductRepository $productRepository, FavorisRepository $favorisRepository, Request $request)
    {
        $user = $this->getUser(); // get the current user
        $product = $productRepository->find($productId);

        // Check if the product is already in the user's favorites
        $favori = $favorisRepository->findOneBy(['users' => $user, 'products' => $product]);

        if ($favori) {
            // If it is, remove it
            $em->remove($favori);
        } else {
            // If it's not, add it
            $favori = new Favoris();
            $favori->setUsers($user);
            $favori->setProducts($product);
            $em->persist($favori);
        }

        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/favoris/supprimer/{favoriId}', name: 'app_favoris_supprimer')]
    public function supprimerDesFavoris($favoriId, EntityManagerInterface $em, FavorisRepository $favorisRepository, Request $request): Response
    {
        $favori = $favorisRepository->find($favoriId); // get the favori

        // remove the favori from the database
        $em->remove($favori);
        $em->flush();

        // redirect to the favoris page
        return $this->redirect($request->headers->get('referer'));
    }
// mentions legales
    #[Route('/pcfdtl', name: 'app_politiquecfdtl')]
    public function politiquecfdtl(): Response
    {
        return $this->render('public/mentionslegales/politiqueconfidentialites.html.twig', [
        ]);
    }

    #[Route('/TetC', name: 'app_tetc')]
    public function TetC(): Response
    {
        return $this->render('public/mentionslegales/TetC.html.twig', [
        ]);
    }

    #[Route('/cookies', name: 'app_cookies')]
    public function cookies(): Response
    {
        return $this->render('public/mentionslegales/cookies.html.twig', [
        ]);
    }

}