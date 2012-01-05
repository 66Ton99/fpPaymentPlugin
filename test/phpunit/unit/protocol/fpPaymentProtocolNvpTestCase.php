<?php

/**
 * fpPaymentProtocolNvp test case.
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentProtocolNvpTestCase extends sfBasePhpunitTestCase
{
  
  /**
   * @test
   */
  public function toArray()
  {
    $data = 'payment_request_date=Thu+Jan+05+06%3A43%3A04+PST+2012&return_url=https%3A//payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/success%3ForderId%3D1&fees_payer=EACHRECEIVER&ipn_notification_url=https%3A//payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/callback%3ForderId%3D1&sender_email=reach_1325697823_per%4066ton99.org.ua&verify_sign=AY4VRGelUH2AL64ek10J4VIp4l4BAoph2nUKX7wbl7PnV7M73eVb2VSN&test_ipn=1&transaction%5B0%5D.id_for_sender_txn=5UK59831YK611411R&transaction%5B0%5D.receiver=seler1_1325697721_biz%4066ton99.org.ua&cancel_url=https%3A//payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/cancelled%3ForderId%3D1&transaction%5B0%5D.is_primary_receiver=false&pay_key=AP-43531652CU3953123&action_type=PAY&transaction%5B0%5D.id=67A82457AS2732549&transaction%5B0%5D.status=Completed&transaction%5B0%5D.paymentType=SERVICE&transaction%5B0%5D.status_for_sender_txn=Completed&transaction%5B0%5D.pending_reason=NONE&transaction_type=Adaptive+Payment+PAY&transaction%5B0%5D.amount=USD+110.00&status=COMPLETED&log_default_shipping_address_in_transaction=false&charset=windows-1252&notify_version=UNVERSIONED&reverse_all_parallel_payments_on_error=false';
    $obj = new fpPaymentProtocolNvp();
    $arr = array(
      'payment_request_date' => 'Thu Jan 05 06:43:04 PST 2012',
      'return_url' => 'https://payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/success?orderId=1',
      'fees_payer' => 'EACHRECEIVER',
      'ipn_notification_url' => 'https://payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/callback?orderId=1',
      'sender_email' => 'reach_1325697823_per@66ton99.org.ua',
      'verify_sign' => 'AY4VRGelUH2AL64ek10J4VIp4l4BAoph2nUKX7wbl7PnV7M73eVb2VSN',
      'test_ipn' => '1',
      'transaction[0].id_for_sender_txn' => '5UK59831YK611411R',
      'transaction[0].receiver' => 'seler1_1325697721_biz@66ton99.org.ua',
      'cancel_url' => 'https://payment.tonpc.forma-dev.com/frontend_test.php/fpPaymentPayPal/cancelled?orderId=1',
      'transaction[0].is_primary_receiver' => 'false',
      'pay_key' => 'AP-43531652CU3953123',
      'action_type' => 'PAY',
      'transaction[0].id' => '67A82457AS2732549',
      'transaction[0].status' => 'Completed',
      'transaction[0].paymentType' => 'SERVICE',
      'transaction[0].status_for_sender_txn' => 'Completed',
      'transaction[0].pending_reason' => 'NONE',
      'transaction_type' => 'Adaptive Payment PAY',
      'transaction[0].amount' => 'USD 110.00',
      'status' => 'COMPLETED',
      'log_default_shipping_address_in_transaction' => 'false',
      'charset' => 'windows-1252',
      'notify_version' => 'UNVERSIONED',
      'reverse_all_parallel_payments_on_error' => 'false'
    );
    $this->assertEquals($arr, $obj->toArray($data));
  }
}