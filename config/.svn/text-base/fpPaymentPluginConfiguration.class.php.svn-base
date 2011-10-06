<?php

/**
 * fpPaymentPlugin configuration
 *
 * @package    fpPayment
 * @subpackage Base
 * @author 	   Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentPluginConfiguration extends sfPluginConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see sfPluginConfiguration::initialize()
   */
  public function initialize()
  {
    $configFiles = $this->configuration->getConfigPaths('config/fp_payment.yml');
    $config = sfDefineEnvironmentConfigHandler::getConfiguration($configFiles);
    
    foreach ($config as $name => $value) {
      sfConfig::set("fp_payment_{$name}", $value);  
    }
  }
  
}