<?php
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Number'));
$currency = fpPaymentCartContext::getInstance()->getPriceManager()->getCurrency();
echo $form->renderFormTag(url_for('@fpPaymentPlugin_orderReview')); ?>
  <table>
    <?php echo $form ?>
  </table>
  
  <table>
    <tr>
      <td>
        <h2>Billing address</h2>
        <?php echo $customer->getCurrentBillingProfile()->getAddresString() ?>
      </td>
      <td>
        <h2>Shipping address</h2>
        <?php echo $customer->getCurrentShippingProfile()->getAddresString() ?>
      </td>      
    </tr>
    <tr>
      <td>
        <h2>Payment method</h2>
        <?php echo $paymentMethod ?>
      </td>
    </tr>
  </table>
  <table>
    <caption></caption>
    <thead>
      <tr>
        <th>Product</th>
        <th>Unit Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $item) { /* @var $item fpPaymentCart */ $product = $item->getProduct(); ?>
        <tr>
          <td><?php echo $product->getName() ?></td>
          <td><?php echo format_currency($product->getPrice(), $currency) ?></td>
          <td><?php echo $item->getQuantity() ?></td>
          <td><?php echo format_currency($product->getPrice() * $item->getQuantity(), $currency) ?></td>
        </tr>
      <?php } ?>
      <?php if (($subTotal = $cartPriceManager->getSubTotal()) != ($sum = $cartPriceManager->getSum())) { ?>
        <tr>
          <td></td>
          <td></td>
          <td>Subtotal: </td>
          <td>
            <?php echo format_currency($subTotal, $currency) ?>
          </td>
        </tr>
      <?php }?>
      <?php if ($shipping = $cartPriceManager->getShippingPrice()) { ?>
        <tr>
          <td></td>
          <td></td>
          <td>Shipping: </td>
          <td>
            <?php echo format_currency($shipping, $currency) ?>
          </td>
        </tr>
      <?php }?>
      <?php if ($tax = $cartPriceManager->getTaxValue()) { ?>
        <tr>
          <td></td>
          <td></td>
          <td>Tax: </td>
          <td>
            <?php echo format_currency($tax, $currency) ?>
          </td>
        </tr>
      <?php }?>
      <tr>
        <td></td>
        <td></td>
        <td>Sum: </td>
        <td>
          <?php echo format_currency($sum, $currency) ?>
        </td>
      </tr>
    </tbody>
  </table>
  
  <hr />
  <input type="submit" value="Pay" />
</form>