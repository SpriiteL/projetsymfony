<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

     public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('category'),
            AssociationField::new('category')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('comments')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('notes')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
}
