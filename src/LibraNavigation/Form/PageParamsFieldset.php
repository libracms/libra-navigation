<?php

namespace LibraNavigation\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Description of PageForm
 *
 * @author duke
 */
class PageParamsFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = 'params')
    {
        parent::__construct($name);
        $this->add(array(
            'name' => 'alias',
            'options' => array(
                'label' => 'Alias:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'span12',
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            array(
                'name'       => 'alias',
                'required'   => false,
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    new \Zend\Filter\Word\SeparatorToDash(),
                ),
            ),
        );
    }
}
