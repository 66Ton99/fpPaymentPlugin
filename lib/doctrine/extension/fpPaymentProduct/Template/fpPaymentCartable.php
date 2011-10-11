<?php

/**
 * Doctrine extension fpPaymentCartable
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class Doctrine_Template_fpPaymentProduct extends Doctrine_Template
{

  /**
   * Array of options
   *
   * @var array
   */
  protected $_options = array( // TODO optimise 
    'name' => array(
    	'name' => 'name',
      'alias' => null,
      'type' => 'string',
      'length' => 255,
      'options' => array('notnull' => true)
    ),
    'price' => array(
    	'name' => 'price',
      'alias' => null,
      'type' => 'float',
      'length' => null,
      'options' => array('notnull' => true)
    ),
  );

  /**
   * Set table definition for the behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->_options = array_merge($this->_options, sfConfig::get('fp_payment_product_extra_fields', array()));
    
    foreach ($this->_options as $option) {
      if ($option['alias']) {
        $option['name'] .= ' as ' . $option['alias'];
      }
      $this->hasColumn($option['name'], $option['type'], $option['length'], $option['options']);
      if (!empty($option['relations'])) {
        foreach ($option['relations'] as $name => $relOptions) {
          $type = 'has' . ucfirst(strtolower($relOptions['type']));
          unset($relOptions['type']);
          $this->$type($name, $relOptions);
        }
      }
    }
    
    $this->addListener(new Doctrine_Template_Listener_fpPaymentProduct($this->_options));
  }
}

