<?php

/**
 * fpPaymentFunctionsTestCase
 *
 * @package    fpPayment
 * @subpackage Tests
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
    sfConfig::set('fp_payment_callback_user', $stub);
    $this->assertInstanceOf($stub, fpPaymentFunctions::getObjFromConfig('fp_payment_callback_user'));
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
    sfConfig::set('fp_payment_callback_user', 'SomeTestClassWithStaticMethod::someMethod');
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_callback_user'));
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
    sfConfig::set('fp_payment_callback_user', array($stub, 'someMethod'));
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_callback_user'));
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
    
    sfConfig::set('fp_payment_callback_user',
                  array('function' => 'SomeTestClassWithStaticMethod2::someMethod',
                        'parameters' => array($stub),
                        'subFunctions' => array('someMethod')));
    $this->assertEquals($time, fpPaymentFunctions::getObjFromConfig('fp_payment_callback_user'));
  }
}