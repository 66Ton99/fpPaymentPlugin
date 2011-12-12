<?php

/**
 * fpPaymentFunctionsTestCase
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentFunctionsTestCase extends sfBasePhpunitTestCase 
{
  /**
   * Dev hook for custom "setUp" stuff
   */
  protected function _start()
  {
  }

  /**
   * Dev hook for custom "tearDown" stuff
   */
  protected function _end()
  {
  }
  
  /**
   * @test
   */
  public function getObjFromConfig_class()
  {
    $stub = $this->getMockClass('Exception');
    sfConfig::set('fp_payment_customer_callback', $stub);
    $this->assertInstanceOf($stub, fpPaymentFunctions::getObjFromConfig('fp_payment_customer_callback'));
  }
  
	/**
   * @test
   */
  public function getObjFromConfig_callbackStatic()
  {
    $time = time();
    eval("class SomeTestClassWithStaticMethod {
  		public static function someMethod() {
  			return {$time};
  		}
  	}");
    sfConfig::set('fp_payment_customer_callback', 'SomeTestClassWithStaticMethod::someMethod');
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_customer_callback'));
  }
  
	/**
   * @test
   */
  public function getObjFromConfig_callbackFromInstance()
  {
    $time = time();
    $stub = $this->getMock('Exception', array('someMethod'));
    $stub->expects($this->once())
     ->method('someMethod')
     ->will($this->returnValue($time));
    sfConfig::set('fp_payment_customer_callback', array($stub, 'someMethod'));
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_customer_callback'));
  }
  
	/**
   * @test
   */
  public function getObjFromConfig_array()
  {
    $time = time();
    $stub = $this->getMock('Exception', array('someMethod'));
    $stub->expects($this->once())
     ->method('someMethod')
     ->will($this->returnValue($time));
     
    eval('class SomeTestClassWithStaticMethod2 {
  		public static function someMethod($class) {
  			return $class;
  		}
  	}');
    
    sfConfig::set('fp_payment_customer_callback',
                  array('function' => 'SomeTestClassWithStaticMethod2::someMethod',
                        'parameters' => array($stub),
                        'subFunctions' => array('someMethod')));
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_customer_callback'));
  }
  
  /**
   * @test
   */
  public function addConfigsToSystem()
  {
    $arr = array(
      'oneLev' => 'val1',
      'fewLevs' => array(
        'lev2' => 'val2',
        'lev21' => 'val3',
        'subLev' => array(
          'lev3' => 'val4',
          'lev31' => 'val5',
        )
      )
    );
    fpPaymentFunctions::addConfigsToSystem('test_one', $arr);
//    $this->addConfigsToSystem()
  }
}