<?php

namespace VirtualAssembly\semappsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractComponentController extends Controller
{
    abstract function getGraph($id=null);
    abstract function getSfUser($id=null);
    abstract function getSfPassword($id=null);
    abstract function getSfLink($id=null);
    abstract function getElement($id=null);

    /**
     * @param $sfClient
     * @param $componentName
     * @param Request $request
     * @param null $id
     * @return \VirtualAssembly\SemanticFormsBundle\Form\SemanticFormType
     * Génère le formulaire pour un $componentName
     */
    public function getSfForm($sfClient,$componentName,Request $request,$id =null)
    {
        $bundleName = $this->getBundleNameFromRequest($request);
        //common
        $componentConf = $this->getParameter($componentName.'Conf');
        $default_types = $this->container->getParameter('semapps.default_types');
        $ontology_types = $this->container->getParameter('semapps.ontology_types');

        if(array_key_exists('graphuri',$componentConf) && $componentConf['graphuri'] != null)
            $graphURI = $componentConf['graphuri'];
        else
            $graphURI = $this->getGraph($id);

        // Build main form.
        //common idea but spec data
        $options = [
            'login'                 => $this->getSfUser($id),
            'password'              => $this->getSfPassword($id),
            'graphURI'              => $graphURI,
            'client'                => $sfClient,
            'sfConf'               	=> $componentConf,
            'spec'                  => $componentConf['spec'],
            'values'                => $this->getSfLink($id),
            'default_types'         => $default_types,
            'ontology_types'        => $ontology_types
        ];

        if ($componentName == 'person' || $componentName == 'organization'){
          $componentForm = 'VirtualAssembly\semappsBundle\Form\\'.ucfirst($componentName).'Type';
        } else if($bundleName = 'semapps'){
          $componentForm = 'VirtualAssembly\semappsBundle\Form\ComponentType';
        }else{
          $componentForm = $bundleName.'Bundle\Form\\'.ucfirst($componentName).'Type';
        }

        //common
        /** @var \VirtualAssembly\SemanticFormsBundle\Form\SemanticFormType $form */

        $form = $this->createForm(
            $componentForm,
            $this->getElement($id),
            // Options.
            $options
        );

        return $form;
    }

    /**
     * @param $request
     * @return mixed
     * Récupère le nom du bundle dans la requête
     */
    protected function getBundleNameFromRequest($request){
      $re = '/[^\W_]+(?:[\'_-][^\W_]+)*?(?=Bundle)/';
      // return explode("\\Controller",$request->attributes->get('_controller'))[0];
      dump($request->attributes->get('_controller'));
      dump(preg_match_all($re, $request->attributes->get('_controller'), $matches, PREG_SET_ORDER, 0));
      dump($matches);
      return $matches[0][0];
    }
}
