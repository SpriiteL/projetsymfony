<?php

namespace App\Controller\Admin;

use App\Entity\Likes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LikesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Likes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('users')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('product')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
}
