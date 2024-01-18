<?php

namespace App\Form;

use App\Entity\Fds;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FdsAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionnez un produit --',
                'query_builder' => function (EntityRepository $er) use ($options) {

                    $query = $er->createQueryBuilder('p')
                        ->leftJoin('p.fds', 'f')
                        ->where('f.product IS NULL');
                    
                    if ($options['data'] && $options['data']->getProduct()){
                        $query->orWhere('p.id = :product')
                            ->setParameter('product', $options['data']->getProduct());
                    }

                    return $query;
                    // return $er->createQueryBuilder('p')
                    //     ->leftJoin('p.fds', 'f')
                    //     ->where('f.product IS NULL');
                },
                'disabled' => $options['is_edit'],
            ])
            ->add('file', FileType::class, [
                'label' => 'Fiche de sécurité',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'accept' => '.pdf',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Le fichier doit être au format PDF',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fds::class,
            'is_edit' => false,
        ]);
    }
}
