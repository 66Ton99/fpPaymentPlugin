<?php

/**
 * fpPaymentCurl test case.
 * 
 * @package    fpPayment
 * @subpackage Tests
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCurlTestCase extends sfBasePhpunitTestCase
{
  
  /**
   * @var fpPaymentCurl
   */
  private $fpPaymentCurl;

  /**
   * Dev hook for custom "setUp" stuff
   */
  protected function _start()
  {
    $this->fpPaymentCurl = new fpPaymentCurl();
  }

  /**
   * Dev hook for custom "tearDown" stuff
   */
  protected function _end()
  {
  }

  /**
   * Constructs the test case.
   */
  public function __construct()
  {
    $this->fpPaymentCurl = new fpPaymentCurl();
  }

  /**
   * Tests fpPaymentCurl->readHeader()
   */
  public function testReadHeader()
  {
    $header = <<<HEADER
key: value
key witout value
fewKeysWithTheSameNames: val1
fewKeysWithTheSameNames: val2

HEADER;
    
    $this->fpPaymentCurl->readHeader(null, $header);
    $res = array(
      'key' => 'value',
      '0' => 'key witout value',
      'fewkeyswiththesamenames' => array(
      	'val1',
      	'val2'
      )
    );
    
    $this->assertEquals($res, $this->fpPaymentCurl->getHeaders());
  
  }

}

