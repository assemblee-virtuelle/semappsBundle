<?php

namespace VirtualAssembly\semappsBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
use VirtualAssembly\SemanticFormsBundle\Form\UriType;
use VirtualAssembly\SemanticFormsBundle\SemanticFormsBundle;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends SemanticFormType
{

    private $lang = 'fr';
    private $default_types;
    private $ontology_types;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // This will manage form specification.
        parent::buildForm($builder, $options);

        $this->default_types = $options['default_types'];
        $this->ontology_types = $options['ontology_types'];
        
        $combinedTypes = array_merge($this->default_types, $this->ontology_types);
        foreach ($options['sfConf']['fields'] as $key => $value) {
            $rdfType = "";
            $name = $value['value'];

            if (isset($value['form'])){
                $label = $value['form']['languages'][$this->lang];
                $classType = $value['form']['classType'];
                if (isset($classType)){
                    $required = (isset($value['form']['required']) && $value['form']['required'] == true) ? true : false;
                    $addOptions = [];
                    $inputType = $this->processClassType($classType);
                    if ($classType == 'uri' && isset($value['type']) && is_array($value['type'])){
                        $i = 0;
                        foreach ($value['type'] as $strType) {
                            if (isset($combinedTypes[$strType])){
                                if ($i == 0){
                                    $rdfType = $combinedTypes[$strType];
                                } else {
                                    $rdfType = '|'.$combinedTypes[$strType];
                                }
                                $i++;
                            }
                        }
                        if (empty($rdfType)){
                            $rdfType == null;
                        }
                        $addOptions = [
                            'required' => $value['form']['required'],
                            'rdfType' => $rdfType,
                            'label' => $label
                        ];
                    } else {
                        $addOptions = [
                            'required' => $value['form']['required'],
                            'label' => $label
                        ];
                    }
                    $this->add(
                        $builder, 
                        $name,
                        $inputType,
                        $addOptions
                        );
                }
            }
        }
        $builder->add(
            'pictureName',
            FileType::class,
            [
                'data_class' => null,
                'required'   => false,
            ]
        );
        $builder->add('save', SubmitType::class, ['label' => 'Enregistrer']);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'client'   => '',
                'login'    => '',
                'password' => '',
                'graphURI' => '',
                'values'   => '',
                'spec'     => '',
                'role'     => '',
                'sfConf'     => '',
                'ontology_types' => '',
                'default_types' => ''
            )
        );
    }

    protected function processClassType($type){
        $classType = null;

        switch ($type) {
            case 'text':
                $classType = TextType::class;
                break;
            case 'textarea':
                $classType = TextareaType::class;
                break;
            case 'multiple':
                $classType = MultipleType::class;
                break;
            case 'uri':
                $classType = UriType::class;
                break;
            case 'file':
                $classType = FileType::class;
                break;
            case 'email':
                $classType = EmailType::class;
                break;
            case 'address':
                $classType = AdresseType::class;
                break;
            case 'dbpedia':
                $classType = DbPediaType::class;
                break;
            default:
                break;
        }
        return $classType;
    }
}