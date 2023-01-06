<?php

namespace App\Form;

use App\Entity\Review;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_email', EmailType::class, [
                'label' => 'Votre e-mail',
                'priority' => 5
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Votre pseudo',
                'priority' => 4
            ])
            ->add('user_rating', ChoiceType::class, [
                'label' => 'Notez le produit',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'priority' => 3
            ])
            ->add('comment', CKEditorType::class, [
                'label' => 'Votre commentaire',
                'priority' => 2
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image (optionnelle)',
                // optionnal so the user don't have to re-upload when editing the comment
                'required' => false,
                // unmapping this field so that Symfony doesn't try to get/set its value from the Review Entity
                'mapped' => false,
                // unmapped fields cannot be validated in the entity, so the validation comes here
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'maxSizeMessage' => 'Votre fichier d\'image est trop lourd, il doit faire {{ limit }} ou moins.',
                        'mimeTypesMessage' => 'Merci de choisir un fichier d\'image valide (JPG, PNG, BMP...)',
                    ])
                    ],
                'priority' => 1
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'priority' => 0,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            // using the HTML-sanitizer bundle to sanitize user input in this form
            'sanitize_html' => true,
        ]);
    }
}
