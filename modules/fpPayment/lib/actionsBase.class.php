<?php

/**
 * Checkout actions.
 *
 * @package    fpPayment
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentActionsBase extends sfActions
{
  
  /**
   * Order status
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeOrderStatus(sfWebRequest $request)
  {
    if (!($id = $request->getParameter('id')) || !($this->order = fpPaymentOrderTable::getInstance()->findOneById($id))) {
      return $this->forward404();
    }
  }
}