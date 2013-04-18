<?php
/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of PageFilter
 *
 * @author duke
 */
class PageFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
            'name'       => 'label',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim',),
            ),
        ));
        $this->add(array(
            'name'       => 'title',
            'required'   => false,
            'filters'    => array(
                array('name' => 'StringTrim',),
            ),
        ));
        $this->add(array(
            'name'       => 'route',
            'required'   => true,
            'filters'    => array(
                array('name' => 'StringTrim',),
            ),
        ));
        $this->add(array(
            'name'       => 'order',
            'required'   => false,
            'filters'    => array(
                array('name' => 'Int'),
            ),
        ));
    }
}
