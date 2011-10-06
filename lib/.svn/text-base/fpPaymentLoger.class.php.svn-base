<?php

/**
 * fpPayment loger
 *
 * @package  fpPayment
 * @author 	 Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentLoger
{
  
  /**
   * Log extension
   *
   * @var string
   */
  protected $extension = '.log';
  
  /**
   * Name
   *
   * @var string
   */
  protected $name;
  
  /**
   * Loger constructor
   *
   * @param string $name
   *
   * @return void
   */
  public function __construct($name)
  {
    $this->name = $name;
  }
  
  public function getName()
  {
    return $this->name;
  }
  
  public function getExtension()
  {
    return $this->extension;
  }
  
  public function getFilePath()
  {
    return sfConfig::get('sf_log_dir') . '/' . $this->getName() . $this->getExtension();
  }
  
  public function add($message, $section = null)
  {
    $message = date('Y-m-d H:i:s') . ' IP ' . $_SERVER['REMOTE_ADDR'] . ' ' . $message;
    if ($section) {
      $message = "============ {$section} ============\n" . $message;
      $message .= "\n=========== \\{$section}\\ ===========\n";
    }
    file_put_contents($this->getFilePath(), $message . "\n\n", FILE_APPEND);
//    @chmod($this->getFilePath(), 0777);
  }
  
  public function addArray($array, $message = '', $section = null)
  {
    $this->add(($message?$message . ': ':'') . print_r($array, true), $section);
  }
}