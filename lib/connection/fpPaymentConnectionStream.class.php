<?php

/**
 * Stream connection protocol
 *
 * @todo finish
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentConnectionStream extends fpPaymentConnectionBase
{
  
  protected $url;
  
  protected $params = array(
    'http' => array(
      'method' => null,
      'content' => null,
      'header' => null
    )
  );
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::__construct()
   */
  public function __construct($url)
  {
    $this->url = trim($url);
  }

  /**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::sendPostRequest()
   */
  public function sendPostRequest($data)
  {
    $fp = null;
    try {
      $params = $this->params;
      $params['http']['method'] = 'POST'; 
      $params['http']['content'] = $data; 
      $params['http']['header'] = $this->getHeadersString();
      $ctx = stream_context_create($params);
      if (!($fp = @fopen($this->url, 'r', false, $ctx))) {
        $this->addTextError('Can\'t open ' . $this->url);
        return '';
      }
      $this->responce = stream_get_contents($fp);
      if (false === $this->responce) {
        $this->addTextError($php_errormsg);
      }
    } catch (Exception $e) {
      $this->addTextError($e->getMessage());
    }
    fclose($fp);
    return $this->responce;
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::sendGetRequest()
   */
  public function sendGetRequest($data)
  {
    throw new sfException('Need to implement'); // TODO
  }
  
  /**
   * Convert array of headers to appropriate string
   *
   * @return string
   */
  protected function getHeadersString()
  {
    $return = '';
    foreach ($this->header as $key => $val) {
      $return .= urlencode($key) . ': '. urlencode($val) . "\r\n";
    }
    return $return;
  }
}

