<?php

/**
 * fpPayment abstract Context class for payment modules
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentMethodContext
{
  
//  abstract const NAME = '';
  
  protected $ipn;
  
  protected $loger;
  
  
  /**
   * Constructor
   *
   * @return void
   */
  public function __construct()
  {
    $this->getContext()->getDispatcher()
      ->connect('fp_payment.befor_process', array(fpPaymentOrderTable::getInstance(), 'createOrder'));
  }
  
  /**
   * Rendering info page
   *
   * @param sfAction $action
   * @param sfWebRequest $request
   *
   * @return sfView
   */
  abstract public function renderInfoPage(sfAction &$action, sfRequest $request);
  
  /**
   * Rendering success page
   *
   * @param sfAction $action
   * @param sfWebRequest $request
   *
   * @return sfView
   */
  abstract public function renderSuccessPage(sfAction &$action, sfRequest $request);
  
  /**
   * Rendering error page
   *
   * @param sfAction $action
   * @param sfWebRequest $request
   *
   * @return sfView
   */
  abstract public function renderErrorPage(sfAction &$action, sfRequest $request);
  
	/**
   * IPN
   *
   * @return fpPaymentIpnBase
   */
  public function getIpn($options = array())
  {
    if (!isset($this->ipn)) {
      $baseConfKey = 'fp_payment_' . strtolower(static::NAME);
      $defIpn = sfConfig::get($baseConfKey . '_ipn_default');
      $ipnClassName = 'fpPayment' . static::NAME . 'Ipn';
      if (empty($defIpn)) {
        $this->ipn = new $ipnClassName($options);
      } else {
        $ipnObjClassName = $ipnClassName . ucfirst($defIpn);
        $this->ipn = new $ipnClassName(new $ipnObjClassName($options));
      }
    }
    return $this->ipn;
  }
  
  /**
   * Loger
   *
   * @return fpPaymentLoger
   */
  public function getLoger()
  {
    if (!isset($this->loger)) {
      $this->loger = new fpPaymentLoger(static::NAME);
    }
    return $this->loger;
  }
  
  /**
   * Get dispatcher
   *
   * @return fpPaymentContext
   */
  public function getContext()
  {
    return fpPaymentContext::getInstance();
  }
  
  /**
   * Do paymet process
   *
   * @param array $values
   *
   * @return fpPaymentMethodContext
   */
  public function doProcess($values)
  {
    $this->getIpn(); // Init IPN
    $values = new ArrayObject($values);
    $this->getContext()->getDispatcher()->notify(new sfEvent($this, 'fp_payment.befor_process', array(
      'context' => $this->getContext(),
      'values' => $values,
    )));
    $this->getContext()->getDispatcher()->notify(new sfEvent($this, 'fp_payment.on_process', array(
      'context' => $this->getContext(),
      'values' => $values,
    )));
    $ipn = $this->getIpn()
      ->addData($values)
      ->process();
    $this->getContext()->getDispatcher()->notify(new sfEvent($this, 'fp_payment.after_process', array(
      'context' => $this->getContext(),
    )));
    if ($ipn->hasErrors()) {
      $this->getContext()->getDispatcher()->notify(new sfEvent($this, 'fp_payment.after_process_error', array(
        'context' => $this->getContext(),
      )));
    } else {
      $this->getContext()->getDispatcher()->notify(new sfEvent($this, 'fp_payment.after_process_success', array(
        'context' => $this->getContext(),
      )));
    }
    return $this;
  }
}
