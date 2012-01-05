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
  
  protected $header = array();
  
  protected $responceInfo = array();
  
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
   * @param string $data
   * 
   * @return string
   */
  abstract public function sendPostRequest($data);
  
  /**
   * Send GET request
   *
   * @param string $data
   *
   * @return string
   */
  abstract public function sendGetRequest($data);
  
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
  
  /**
   * Set request header
   *
   * @param array $header
   *
   * @return fpPaymentConnectionBase
   */
  public function setHeader(array $header)
  {
    $this->header = $header;
    return $this;
  }
  
	/**
   * Get responce info
   *
   * @return array - posible keys: url, http_code, redirect_count ...
   */
  public function getResponseInfos()
  {
    return $this->responceInfo;
  }
  
	/**
	 * Get info item
	 *
	 * @param string $key
	 *
	 * @return string
	 */
  public function getResponseInfo($key)
  {
    if (!isset($this->responceInfo[$key])) return null;
    return $this->responceInfo[$key];
  }
  
  /**
   * Convert $data to string
   *
   * @param mixed $data
   *
   * @return string
   */
  public function toString($data)
  {
    return print_r($data, true);
  }
}

