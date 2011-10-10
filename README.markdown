# fpPaymentPlugin

It depends on sfDoctrineGuardPlugin but connection does not hardcoded 

sfSslRequirementPlugin - recommended plugin for ssl support

You have to enable "fpPaymentCheckout" module
 
_settings.yml_

    all:
      .settings:
        enabled_modules:
          - fpPaymentCheckout
    
_Events_

    fp_payment.befor_process
    @var $context fpPaymentContext
    @var $values array
    
    fp_payment_order.befor_create
    @var $context fpPaymentContext
    @var $values array
    
    fp_payment_order.after_create
    @var $context fpPaymentContext
    @var $values array
    
    fp_payment.on_process:
    @var $context fpPaymentContext
    @var $values array
    
    fp_payment.after_process
    @var $context fpPaymentContext
    
    fp_payment.after_process_error OR fp_payment.after_process_success
    @var $context fpPaymentContext
    
