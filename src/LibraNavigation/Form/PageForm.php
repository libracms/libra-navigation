<?php

namespace LibraNavigation\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Description of PageForm
 *
 * @author duke
 */
class PageForm extends Form
{
    public function __construct($name = 'page-form')
    {
        parent::__construct($name);
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'value' => '0',
            ),
        ));
        $this->add(array(
            'name' => 'label',
            'options' => array(
                'label' => 'Label: *',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Title:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
        $this->add(array(
            'name' => 'route',
            'options' => array(
                'label' => 'Route: *',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
//        $fildset = new \Zend\Form\Fieldset('params');
//        $fildset->add(array(
//            'name' => 'alias',
//            'options' => array(
//                'label' => 'Alias:',
//            ),
//            'attributes' => array(
//                'type' => 'text',
//                'class' => 'span12',
//            ),
//        ));
        $fildset = new PageParamsFieldset();
        $this->add($fildset);
//        $this->add(array(
//            'name' => 'alias',
//            'options' => array(
//                'label' => 'Alias:',
//            ),
//            'attributes' => array(
//                'type' => 'text',
//                'class' => 'span12',
//            ),
//        ));
        $this->add(array(
            'name' => 'order',
            'options' => array(
                'label' => 'Order:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
        $this->add(array(
            'name' => 'controller',
            'options' => array(
                'label' => 'Controller:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
        $this->add(array(
            'name' => 'action',
            'options' => array(
                'label' => 'Action:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'options' => array(
            ),
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save',
                'formmethod' => 'POST',
            ),
        ));

        $csrf = new Element\Csrf('csrf');
        $this->add($csrf);
    }
}
