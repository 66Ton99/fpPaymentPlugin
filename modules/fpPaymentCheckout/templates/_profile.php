<?php echo $form->renderFormTag(url_for('@fpPaymentPlugin_profile')) ?>
  <table>
    <?php echo $form ?>
  </table>
  <hr />
  <input type="hidden" name="is_billing" value="<?php echo sfContext::getInstance()->getRequest()->getParameter('is_billing') ?>" />
  <input type="submit" value="Next" />
</form>