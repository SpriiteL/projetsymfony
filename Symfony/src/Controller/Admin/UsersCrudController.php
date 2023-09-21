<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('notes'),
            AssociationField::new('notes')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            AssociationField::new('comments')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('likes')
            ->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
    
}
