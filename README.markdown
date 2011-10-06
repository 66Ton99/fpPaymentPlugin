# fpPaymentPlugin

It depends on sfDoctrineGuardPlugin but connection does not hardcoded 

sfSslRequirementPlugin - recommended plugin for ssl support

You have to enable "fpPaymentCheckout" module
 
_settings.yml_

    all:
      .settings:
        enabled_modules:
          - fpPaymentCheckout
    