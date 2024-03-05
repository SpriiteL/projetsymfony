<?php

namespace App\Controller\Admin;

use App\Form\SellingFormType;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('description'),
            ImageField::new('imagefile')
                ->setBasePath('/img/product'),
            TextField::new('color'),
            TextField::new('condition_product'),
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setFormTypeOptions(['currency' => 'EUR']),
            AssociationField::new('users'),
            AssociationField::new('category')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
    
}
