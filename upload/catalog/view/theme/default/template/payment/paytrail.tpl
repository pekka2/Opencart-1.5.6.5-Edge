<form action="<?php echo $action;?>" method="post" id="paytrail">
  <input type="hidden" name="MERCHANT_ID" value="<?php echo $MERCHANT_ID;?>"/>
  <input type="hidden" name="URL_SUCCESS" value="<?php echo $URL_SUCCESS;?>"/>
  <input type="hidden" name="URL_CANCEL" value="<?php echo $URL_CANCEL;?>"/>
  <input type="hidden" name="ORDER_NUMBER" value="<?php echo $ORDER_NUMBER;?>"/>
  <input type="hidden" name="PARAMS_IN" value="<?php echo $PARAMS_IN;?>"/>
  <input type="hidden" name="PARAMS_OUT" value="<?php echo $PARAMS_OUT;?>"/>
  <?php if(!$journal_checkout){ 
     foreach($products as $num=>$product){
        foreach($product as $key=>$value){?>
          <input type="hidden" name="<?php echo $key?>[<?php echo $num;?>]" value="<?php echo $value;?>"/>
        <?php }
     }
  }
  ?>
    <input name="MSG_UI_MERCHANT_PANEL" type="hidden" value="<?php echo $MSG_UI_MERCHANT_PANEL;?>"/>
    <input name="URL_NOTIFY" type="hidden" value="<?php echo $URL_NOTIFY;?>"/>
    <input name="LOCALE" type="hidden" value="<?php echo $LOCALE;?>"/>
    <input name="CURRENCY" type="hidden" value="<?php echo $CURRENCY;?>"/>
    <input name="REFERENCE_NUMBER" type="hidden" value="<?php echo $REFERENCE_NUMBER;?>"/>
    <input name="PAYMENT_METHODS" type="hidden" value=""/>
  <?php if(!$journal_checkout){ ?>
  <input type="hidden" name="PAYER_PERSON_PHONE" value="<?php echo $PAYER_PERSON_PHONE;?>"/>
  <input type="hidden" name="PAYER_PERSON_EMAIL" value="<?php echo $PAYER_PERSON_EMAIL;?>"/>
  <input type="hidden" name="PAYER_PERSON_FIRSTNAME" value="<?php echo $PAYER_PERSON_FIRSTNAME;?>"/>
  <input type="hidden" name="PAYER_PERSON_LASTNAME" value="<?php echo $PAYER_PERSON_LASTNAME;?>"/>
<?php if($PAYER_COMPANY_NAME){?>
  <input type="hidden" name="PAYER_COMPANY_NAME" value="<?php echo $PAYER_COMPANY_NAME;?>"/>
<?php } ?>
  <input type="hidden" name="PAYER_PERSON_ADDR_STREET" value="<?php echo $PAYER_PERSON_STREET;?>"/>
  <input type="hidden" name="PAYER_PERSON_ADDR_POSTAL_CODE" value="<?php echo $PAYER_PERSON_ADDR_POSTAL_CODE;?>"/>
  <input type="hidden" name="PAYER_PERSON_ADDR_TOWN" value="<?php echo $PAYER_PERSON_ADDR_TOWN;?>"/>
  <input type="hidden" name="PAYER_PERSON_ADDR_COUNTRY" value="<?php echo $PAYER_PERSON_ADDR_COUNTRY;?>"/>
  <input type="hidden" name="VAT_IS_INCLUDED" value="<?php echo $VAT_IS_INCLUDED;?>"/>
 <?php } ?>
  <input type="hidden" name="AMOUNT" value="<?php echo $AMOUNT;?>"/>
  <input type="hidden" name="ALG" value="<?php echo $ALG;?>"/>
  <input type="hidden" name="AUTHCODE" value="<?php echo $AUTHCODE;?>"/>
<div class="buttons">
  <div class="right">
    <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
</form> 
