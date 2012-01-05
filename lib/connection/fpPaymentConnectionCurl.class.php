<?php

/**
 * Curl connection protocol
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentConnectionCurl extends fpPaymentConnectionBase
{

  private $curlHandler;

  /**
   * Response headers
   *
   * @var array
   */
  protected $responceHeaders = array();
  
  /**
   * Curl output buffer
   *
   * @var string
   */
  protected $response = '';

  protected $options = array();

  protected $customOptions = array();
  
  protected $isRedirect = false;
  
  protected $connections = array();

  protected $request = '';

  protected $curlMultiHandler;

  /**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::__construct()
   */
  public function __construct($url)
  {
    $this->options['CURLOPT_USERAGENT'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.6) Gecko/20060728 Firefox/1.5.0.6';
    $this->options['CURLOPT_URL'] = $url;
    $this->options['CURLOPT_RETURNTRANSFER'] = 1;
    $this->options['CURLOPT_TIMEOUT'] = 10;
    $this->options['CURLOPT_SSL_VERIFYHOST'] = 0;
    $this->options['CURLOPT_SSL_VERIFYPEER'] = 0;
    $this->options['CURLOPT_NOBODY'] = 1;
    $this->options['CURLOPT_HEADER'] = 0;
    $this->options['CURLOPT_HEADERFUNCTION'] = array(&$this, 'readHeader');
  }

  /**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::sendPostRequest()
   */
  public function sendPostRequest($data)
  {
    $this->curlHandler = curl_init();
    
    $this->options['CURLOPT_POST'] = 1;
    $this->options['CURLOPT_POSTFIELDS'] = $data;
    $this->process();
    
    unset($this->options['CURLOPT_POST']);
    unset($this->options['CURLOPT_POSTFIELDS']);
    
    return $this->response;
  }
  
	/**
   * (non-PHPdoc)
   * @see fpPaymentConnectionBase::sendGetRequest()
   */
  public function sendGetRequest($data)
  {
    $this->curlHandler = curl_init();
    $orUrl = $this->options['CURLOPT_URL'];
    if (!empty($data)) {
      $this->options['CURLOPT_URL'] .= '?' . $data;
    }
    $this->process();
    $this->options['CURLOPT_URL'] = $orUrl;
    return $this->response;
  }

  /**
   * Callbeck function. Collect headers
   *
   * @param curl $curl
   * @param string $header
   *
   * @return int
   */
  public function readHeader($curl, $header)
  {
    if (!empty($header)) {
      foreach (explode("\n", $header) as $headerLine) {
        $parts = explode(':', $headerLine);
        if (count($parts) >= 2) {
          $key = strtolower(trim(array_shift($parts)));
          $value = trim(implode(':', $parts));
          if (empty($value)) continue;
          if (isset($this->responceHeaders[$key]) && !is_array($this->responceHeaders[$key])) {
            $this->responceHeaders[$key] = array($this->responceHeaders[$key]);
          }
          if (isset($this->responceHeaders[$key]) && is_array($this->responceHeaders[$key])) {
            $this->responceHeaders[$key][] = $value;
          } else {
            $this->responceHeaders[$key] = $value;
          }
        } elseif ('' != ($value = trim($headerLine))) {
          $this->responceHeaders[] = $value;
        }
      }
    }
    return strlen($header);
  }

  /**
   * Process request
   *
   * @return bool
   */
  protected function process()
  {
    
    if (!is_resource($this->curlHandler)) {
      $this->addTextError('Curl has not be initialized');
      return false;
    }
    
    // setting up curl options
    $options = array_merge($this->options, $this->customOptions);
    foreach ($options as $key => $values) {
      curl_setopt($this->curlHandler, constant($key), $values);
    }
    
    if (!empty($this->header)) {
      $header =  array();
      foreach ($this->header as $key => $val) {
        $header[] = $key . ': ' . urlencode($val);
      }
      curl_setopt($this->curlHandler, CURLOPT_HTTPHEADER, $header);
    }
    
    // process request
    $response = curl_exec($this->curlHandler);
    $this->customOptions = array();
    $this->responceInfo = curl_getinfo($this->curlHandler);
    
    if ($this->responceInfo['http_code'] != 200) {
      $this->addTextError('HTTP code not 200 but ' . $this->responceInfo['http_code']);
    }
    
    if ($this->hasHeader('Location')) {
      $this->isRedirect = true;
    } elseif (empty($response)) {
      $this->addTextError('Null size reply ' . curl_error($this->curlHandler));
      curl_close($this->curlHandler);
      return false;
    }
    
    if (curl_errno($this->curlHandler) == 0) {
      curl_close($this->curlHandler);
      $this->response = $response;
      return true;
    }
    $this->addTextError('Error, while process request: ' . curl_error($this->curlHandler));
    curl_close($this->curlHandler);
    return false;
  }
  
  /**
   * Return sended request
   *
   * @return string
   */
  public function getRequest()
  {
    return $this->request;
  }
  
  /**
   * Return response
   *
   * @return string
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * 
   * 
   * @param string $urls
   * 
   * @todo finish
   *
   * @return array
   */
  public function setMultiRequest($urls)
  {
    if (empty($urls)) {
      $this->addTextError('Curl: Multi init error');
      return;
    }
    
    if (isset($this->options['CURLOPT_URL'])) {
      unset($this->options['CURLOPT_URL']);
    }
    
    $this->curlMultiHandler = curl_multi_init();
    foreach ($urls as $url => $params) {
      $this->connections[$url] = curl_init($url);
      
      // set url
      $this->options['CURLOPT_URL'] = $url;
      if (!empty($params['GET'])) {
        $url_query = $this->prepareRequest($params['GET']);
        if (!empty($url_query)) {
          $this->options['CURLOPT_URL'] .= '?' . $url_query;
        }
      }
      
      // set common options
      foreach ($this->options as $code => $value) {
        curl_setopt($this->connections[$url], constant($code), $value);
      }
      
      // process redirects
      curl_setopt($this->connections[$url], CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($this->connections[$url], CURLOPT_MAXREDIRS, 1);
      
      // set post method and data if need
      if (!empty($params['POST'])) {
        curl_setopt($this->connections[$url], CURLOPT_POST, 1);
        curl_setopt($this->connections[$url], CURLOPT_POSTFIELDS, $this->prepareRequest($params['POST']));
      }
      curl_multi_add_handle($this->curlMultiHandler, $this->connections[$url]);
    }
    
    // start performing the request
    do {
      $mrc = curl_multi_exec($this->curlMultiHandler, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    
    while ($active and $mrc == CURLM_OK) {
      // wait for network
      if (curl_multi_select($this->curlMultiHandler) != -1) {
        // pull in any new data, or at least handle timeouts
        do {
          $mrc = curl_multi_exec($this->curlMultiHandler, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
      }
    }
    
    $res = array();
    
    // retrieve data
    foreach ($urls as $url => $params) {
      if (($err = curl_error($this->connections[$url])) == '') {
        $res[$url] = curl_multi_getcontent($this->connections[$url]);
      } else {
        $this->addTextError("Curl error on handle {$url}: {$err}\n");
      }
      curl_multi_remove_handle($this->curlMultiHandler, $this->connections[$url]);
      curl_close($this->connections[$url]);
    }
    
    curl_multi_close($this->curlMultiHandler);
    
    return $res;
  }

  /**
   * Set option
   *
   * @param string $option_name
   * @param string $value
   *
   * @return fpPaymentConnectionCurl
   */
  public function setOption($option_name, $value)
  {
    $this->customOptions[$option_name] = $value;
    return $this;
  }
  
  /**
   * Return headers of response
   *
   * @return array
   */
  public function getHeaders()
  {
    return $this->responceHeaders;
  }
  
  /**
   * Check header present
   *
   * @param string $key
   *
   * @return bool
   */
  public function hasHeader($key)
  {
    $key = strtolower($key);
    return isset($this->responceHeaders[$key]);
  }
  
  /**
   * Return header of response
   *
   * @return mixed
   */
  public function getHeader($key)
  {
    $key = strtolower($key);
    if ($this->hasHeader($key)) {
      return $this->responceHeaders[$key];
    }
    return false;
  }
  
  /**
   * Checks redirect or not
   *
   * @return bool
   */
  public function isRedirect()
  {
    return $this->isRedirect;
  }
}

