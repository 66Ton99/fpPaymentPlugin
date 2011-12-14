<?php

/**
 * fpPaymentConnectionCurl test case.
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentConnectionCurlTestCase extends sfBasePhpunitTestCase
{
  
  /**
   * @var fpPaymentConnectionCurl
   */
  private $fpPaymentConnectionCurl;

  protected function _start()
  {
    $this->fpPaymentConnectionCurl = new fpPaymentConnectionCurl('');
  }

  /**
   * Tests fpPaymentConnection->readHeader()
   */
  public function testReadHeader()
  {
    $header = <<<HEADER
key: value
key witout value
fewKeysWithTheSameNames: val1
fewKeysWithTheSameNames: val2

HEADER;
    
    $this->fpPaymentConnectionCurl->readHeader(null, $header);
    $res = array(
      'key' => 'value',
      '0' => 'key witout value',
      'fewkeyswiththesamenames' => array(
      	'val1',
      	'val2'
      )
    );
    
    $this->assertEquals($res, $this->fpPaymentConnectionCurl->getHeaders());
  
  }

}

