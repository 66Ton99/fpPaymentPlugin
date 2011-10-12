# fpPaymentPlugin

## Overview

The basic functional of e-commerce: order, product

## Requirements

* [Symfony](http://www.symfony-project.org) 1.4
* sfDoctrineGuardPlugin - connection does not hardcoded 
* sfSslRequirementPlugin - recommended plugin for ssl support (optional)
* fpPaymentTaxPlugin - (optional)
* fpPaymentCartPlugin - (optional)
* fpPaymentAuthorizePlugin - (optional)
* fpPaymentPayPalPlugin - (optional)

## Getting Started

You have to enable "fpPaymentCheckout" module
 
_settings.yml_

    all:
      .settings:
        enabled_modules:
          - fpPaymentCheckout
    
"product" table must have fpPaymentProduct behaviour

_schema.yml_

    Product:
      actAs:
        fpPaymentProduct: ~
      columns:
        some_other_field: {type: integer, notnull: true}
        
You have to implement fpPaymentProfileble behavior to your Customer model if you want use Profiles and Taxes
        
_sfGuardUser.class.php_
        
    public function setUp()
    {
      parent::setUp();
      $this->actAs(new Doctrine_Template_fpPaymentProfileble(array()));
    }    
    
    
## Features

### Events

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