<?php

namespace VirtualAssembly\semappsBundle\Form;

use VirtualAssembly\semappsBundle\coreConfig;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use VirtualAssembly\SemanticFormsBundle\Form\AdresseType;
use VirtualAssembly\SemanticFormsBundle\Form\DbPediaType;
use VirtualAssembly\SemanticFormsBundle\Form\MultipleType;
use VirtualAssembly\SemanticFormsBundle\Form\SemanticFormType;
use VirtualAssembly\SemanticFormsBundle\Form\ThesaurusType;
use VirtualAssembly\SemanticFormsBundle\Form\UriType;

class OrganizationType extends ComponentType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // This will manage form specification.
        parent::buildForm($builder, $options);
    }
}
