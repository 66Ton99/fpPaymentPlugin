<?php

/**
 * Curl
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCurl
{

  private $curlHandler;
  
  /**
   * Error's spool
   *
   * @var array
   */
  protected $errors = array();

  /**
   * Curl output buffer
   *
   * @var string
   */
  protected $response = '';

  protected $options = array();

  protected $customOptions = array();
  
  protected $isRedirect = false;

  /**
   * Response headers
   *
   * @var array
   */
  protected $headers = array();

  protected $connections = array();

  protected $request = '';
  
  protected $requestInfo = array();

  protected $curlMultiHandler;

  /**
   * Constructor
   *
   * @param string $url
   *
   * @return void
   */
  public function __construct($url = '')
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
   * Send POST request
   * 
   * @param array $data
   * @param string $url
   * @return string
   */
  public function sendPostRequest($data = array(), $url = '')
  {
    $this->curlHandler = curl_init();
    
    if (!empty($url)) {
      $this->options['CURLOPT_URL'] = $url;
    }
    $this->options['CURLOPT_POST'] = 1;
    $this->options['CURLOPT_POSTFIELDS'] = $this->prepareRequest($data);
    $this->process();
    
    unset($this->options['CURLOPT_POST']);
    unset($this->options['CURLOPT_POSTFIELDS']);
    
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
          if (isset($this->headers[$key]) && !is_array($this->headers[$key])) {
            $this->headers[$key] = array($this->headers[$key]);
          }
          if (isset($this->headers[$key]) && is_array($this->headers[$key])) {
            $this->headers[$key][] = $value;
          } else {
            $this->headers[$key] = $value;
          }
        } elseif ('' != ($value = trim($headerLine))) {
          $this->headers[] = $value;
        }
      }
    }
    return strlen($header);
  }

  /**
   * Send GET request
   *
   * @param array $data
   * @param string $url
   *
   * @return string
   */
  public function sendGetRequest($data = array(), $url = '')
  {
    $this->curlHandler = curl_init();
    if (!empty($url)) {
      $this->options['CURLOPT_URL'] = $url;
    }
    $url_query = $this->prepareRequest($data);
    if (!empty($url_query)) {
      $this->options['CURLOPT_URL'] .= '?' . $url_query;
    }
    
    $this->process();
    return $this->response;
  }

  protected function process()
  {
    
    if (!is_resource($this->curlHandler)) {
      $this->setTextError('Curl has not be initialized');
      return false;
    }
    
    // setting up curl options
    $options = array_merge($this->options, $this->customOptions);
    foreach ($options as $key => $values) {
      curl_setopt($this->curlHandler, constant($key), $values);
    }
    
    // process request
    $response = curl_exec($this->curlHandler);
    $this->customOptions = array();
    $this->requestInfo = curl_getinfo($this->curlHandler);
    
    if ($this->requestInfo['http_code'] != 200) {
      $response = '';
    }
    
    if ($this->hasHeader('Location')) {
      $this->isRedirect = true;
    } elseif (empty($response)) {
      $this->setTextError('Null size reply ' . curl_error($this->curlHandler));
      curl_close($this->curlHandler);
      return false;
    }
    
    if (curl_errno($this->curlHandler) == 0) {
      curl_close($this->curlHandler);
      return $this->response = $response;
    } else {
      $this->setTextError('Error, while process request: ' . curl_error($this->curlHandler));
      curl_close($this->curlHandler);
      return false;
    }
  }

  public function setTextError($err)
  {
    $this->errors[] = $err;
  }

  public function hasErrors()
  {
    return !empty($this->errors)?true:false;
  }
  
  /**
   * Get errors
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Convert array data to string
   *
   * @param array $data
   *
   * @return string
   */
  public function prepareRequest($data)
  {
    if (empty($data) || !is_array($data)) return '';
    $parts = array();
    foreach ($data as $field => $value) {
      $parts[] = $field . '=' . $value;
    }
    
    return $this->request = implode('&', $parts);
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

  public function setMultiRequest($urls)
  {
    if (empty($urls)) {
      $this->setTextError('Curl: Multi init error');
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
        $this->setTextError("Curl error on handle $url: $err\n");
      }
      curl_multi_remove_handle($this->curlMultiHandler, $this->connections[$url]);
      curl_close($this->connections[$url]);
    }
    
    curl_multi_close($this->curlMultiHandler);
    
    return $res;
  }

  public function setOption($option_name, $value)
  {
    $this->customOptions[$option_name] = $value;
  }
  
  /**
   * Return headers of response
   *
   * @return array
   */
  public function getHeaders()
  {
    return $this->headers;
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
    return isset($this->headers[$key]);
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
      return $this->headers[$key];
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

