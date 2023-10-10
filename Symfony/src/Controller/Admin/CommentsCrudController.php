<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comments::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('users'),
            AssociationField::new('users')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            // IdField::new('id'),
            TextField::new('content'),
            AssociationField::new('content')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            // IdField::new('id'),
            TextField::new('product_id'),
            AssociationField::new('product_id')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
    }
}
