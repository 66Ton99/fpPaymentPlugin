<?php echo $form->renderFormTag(url_for($isBilling?'@fpPaymentPlugin_billing':'@fpPaymentPlugin_shipping')) ?>
    <?php echo $form ?>
  </table>
  <hr />
  <input type="submit" value="Next" />
</form>