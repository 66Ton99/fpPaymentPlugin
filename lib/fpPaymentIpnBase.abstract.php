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
   * URL keys
   * 
   * @var array
   */
  protected $urlsKeys = array();
  
  /**
   * Protocol obj
   * 
   * @var fpPaymentProtocolBase
   */
  protected $protocol;
  
  /**
   * Connectio
   * 
   * @var fpPaymentConnection
   */
  protected $connection;

  /**
   * Constructor
   *
   * @return void
   */
  abstract public function __construct($options = array());
  
  /**
   * Process
   *
   * @return fpPaymentIpnBase
   */
  abstract public function process();
  
  /**
   * Loger
   *
   * @return fpPaymentLoger
   */
  abstract public function getLoger();
  
  /**
   * Get main context
   *
   * @return fpPaymentContext
   */
  protected function getContext()
  {
    return fpPaymentContext::getInstance();
  }
  
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
  
	/**
   * Retrun keys of urls
   *
   * @return array
   */
  public function getUrlKeys()
  {
    return $this->urlsKeys;
  }
  
  /**
   * Convert routes to urls
   *
   * @param array $data
   *
   * @return array
   */
  protected function convertRoutesToUrls($data)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    foreach ($this->getUrlKeys() as $key) {
      $url = $data[$key];
      if (false === strpos($url, '://')) {
        $url = url_for($url, true);
      }
      $data[$key] = $url;
    }
    return $data;
  }
  
  /**
   * Get communication protocol
   *
   * @param string $type
   * 
   * @todo Finished
   *
   * @return fpPaymentProtocol
   */
  protected function getProtocol()
  {
    $type = 'NVP';
    if (empty($this->protocol)) {
      $className = 'fpPaymentProtocol' . ucfirst(strtolower($type));
      $this->protocol = new $className();
    }
    return $this->protocol;
  }
  
  /**
   * Get connection
   *
   * @param string $url
   *
   * @return fpPaymentConnection
   */
  protected function getConnection($url)
  {
    if (empty($this->connection)) {
      $this->connection = new fpPaymentConnection($url);
    }
    return $this->connection;
  }
  
	/**
   * Get url
   * 
   * @param string $path;
   *
   * @return string
   */
  public function getUrl($path = null)
  {
    return '';
  }
}