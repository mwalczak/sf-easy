<?php

declare(strict_types=1);

namespace App\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class ImagesField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('field/images.html.twig')
            ->setFormType(HiddenType::class)->setFormTypeOption('attr.class', 'field-iri')
            ->addCssClass('field-hidden field-iri')
            ->addJsFiles(
                'https://cdnjs.cloudflare.com/ajax/libs/paste.js/0.0.21/paste.min.js',
                'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',
                'js/images.js')
            ->addCssFiles(
                'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css',
                'css/images.css'
            );
    }
}
