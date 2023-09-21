<?php

namespace App\Controller\Admin;

use App\Entity\Notes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class NotesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('users'),
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
