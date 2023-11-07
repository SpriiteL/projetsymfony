<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SellingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('color')
            ->add('condition_product')
            ->add('price')
            ->add('imagefile', FileType::class, [
                'label' => 'Télécharger une image',
                'required' => true,
            ])
            ->add('users')
            ->add('category')
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response{
        if (in_array('ROLE_USER', $token->getUser()->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('users'));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
