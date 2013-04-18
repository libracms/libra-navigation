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
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'title',
            'required'   => false,
            'filters'    => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'route',
            'required'   => true,
            'filters'    => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        $this->add(array(
            'name'       => 'params',
            array(
                'name'       => 'alias',
                'required'   => false,
                'filters'    => array(
                    array('name' => 'StringTrim'),
                    new \Zend\Filter\Word\SeparatorToDash(),
                ),
            )
        ));
/*
array(1) (
  [0] => array(3) (
    [name] => (string) alias
    [required] => (bool) false
    [filters] => array(2) (
      [0] => array(1) (
        [name] => (string) StringTrim
      )
      [1] => Zend\Filter\Word\SeparatorToDash object {
        searchSeparator => (string)
        replacementSeparator => (string) -
        options => array(0)
      }
    )
  )
)
 */
            $this->add(array(
            'name'       => 'order',
            'required'   => false,
            'filters'    => array(
                array('name' => 'Int'),
            ),
        ));
    }
}
