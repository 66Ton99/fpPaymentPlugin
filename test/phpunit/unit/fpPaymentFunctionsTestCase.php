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
   * Data for registerConfigsToSystem_oneLevel
   *
   * @return array
   */
  public function config()
  {
    return array(
      array(
        1,
        array('oneLev' => 'val1',),
        'oneLev',
        'val1'
      ),
      array(
        2,
        array(
          'oneLev' => 'val1',
        ),
        'oneLev',
        'val1'
      ),
      array(
        1,
        array('fewLevels' => array('level2' => 'val1')),
        'fewLevels',
        array('level2' => 'val1')
      ),
      array(
        2,
        array('fewLevels' => array('level2' => 'val1')),
        'fewLevels',
        null
      ),
      array(
        2,
        array('fewLevels' => array('level2' => 'val1')),
        'fewLevels_level2',
        'val1'
      ),
      array(
        3,
        array('fewLevels' => array('level2' => array('level3' => 'val1'))),
        'fewLevels_level2_level3',
        'val1'
      ),
    );
  }
  
  /**
   * @test
   * @dataProvider config
   */
  public function registerConfigsToSystem_oneLevel($level, $source, $request, $res)
  {
    $baseKey = 'test_one';
    fpPaymentFunctions::registerConfigsToSystem($baseKey, $source, $level);
    $this->assertEquals($res, sfConfig::get($baseKey . '_' . $request));
  }
  
  /**
   * Data for arrayMergeRecursive
   *
   * @return array
   */
  public function arrays()
  {
    return array(
      array(
        array(),
        array(),
        array(),
      ),
      array(
        array('one' => 1),
        array('one' => 2),
        array('one' => 2),
      ),
      array(
        array('one' => 1),
        array('two' => 2),
        array('one' => 1, 'two' => 2),
      ),
      array(
        array('one' => array('sub1' => 1, 'sub2' => 2)),
        array('one' => array('sub1' => 3)),
        array('one' => array('sub1' => 3, 'sub2' => 2)),
      ),
      array(
        array('one' => array('sub1' => 3)),
        array('one' => array('sub1' => 1, 'sub2' => 2)),
        array('one' => array('sub1' => 1, 'sub2' => 2)),
      ),
      array(
        array('one' => array('sub1' => array('sub21' => 1))),
        array('one' => array('sub1' => array('sub21' => 12))),
        array('one' => array('sub1' => array('sub21' => 12))),
      ),
    );
  }
  
  /**
   * @test
   * @dataProvider arrays
   */
  public function arrayMergeRecursive($first, $second, $result)
  {
    $this->assertEquals($result, fpPaymentFunctions::arrayMergeRecursive($first, $second));
  }
}