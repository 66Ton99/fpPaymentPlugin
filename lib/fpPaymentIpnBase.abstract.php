<?php

/**
 * fpPayment base class
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentIpnBase
{
  /**
   * Errors
   *
   * @var array
   */
  protected $errors = array();
  
  /**
   * Response
   *
   * @var mixed
   */
  protected $response;
  
  /**
   * Data
   *
   * @var array
   */
  protected $data = array();

  /**
   * Constructor
   *
   * @return void
   */
  abstract public function __construct($options);
  
  /**
   * Process
   *
   * @return fpPaymentIpnBase
   */
  abstract public function process();
  
  /**
   * Set data
   *
   * @param array $data
   *
   * @return fpPaymentIpnBase
   */
  public function setData($data)
  {
    foreach ($data as $key => $val) {
      if (null === $val) {
        unset($data[$key]);
      }
    }
    $this->data = $data;
    return $this;
  }
  
	/**
   * Add data
   *
   * @param array $data
   *
   * @return fpPaymentIpnBase
   */
  public function addData($data)
  {
    return $this->setData(array_merge($this->data, (array)$data));
  }
  
  /**
   * Return data
   *
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }
  
	/**
   * Check errors
   *
   * @return bool
   */
  public function hasErrors()
  {
    return empty($this->errors)?false:true;
  }
  
  /**
   * Get error
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }
  
	/**
   * Responce
   *
   * @return mixed
   */
  public function getResponse()
  {
    return $this->response;
  }
  
}