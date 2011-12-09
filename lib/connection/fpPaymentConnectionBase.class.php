<?php

/**
 * Base connection protocol
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentConnectionBase
{
  
  /**
   * Error's spool
   *
   * @var array
   */
  protected $errors = array();
  
  /**
   * Constructor
   *
   * @param string $url
   *
   * @return void
   */
  abstract public function __construct($url);
  
  /**
   * Send POST request
   * 
   * @param array $data
   * @param string $url
   * @return string
   */
  abstract public function sendPostRequest($data = array());
  
  /**
   * Send GET request
   *
   * @param array $data
   * @param string $url
   *
   * @return string
   */
  abstract public function sendGetRequest($data = array());
  
  /**
   * Add text error
   *
   * @param string $err
   *
   * @return fpPaymentConnectionBase
   */
  public function addTextError($err)
  {
    $this->errors[] = $err;
    return $this;
  }

  /**
   * Check errors present
   *
   * @return bool
   */
  public function hasErrors()
  {
    return !empty($this->errors)?true:false;
  }
  
	/**
   * Get all errors
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }
}

