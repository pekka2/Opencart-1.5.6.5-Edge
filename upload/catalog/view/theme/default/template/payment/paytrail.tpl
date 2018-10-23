<fieldset>
<form id="paytrail">
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
</fieldset>
<script src="catalog/view/javascript/jquery/payment-widget.min.js" type="text/javascript"></script>
<script type="text/javascript">
   var screenWidth = $(window).width();
   var paddings = 62; // etusivun yleiset sivupaddingit
   var column_padding = 24; // sivupalkin yleiset sivupaddingit
   var padding_1 = 31; // tekstialueen yleinen left padding
   var padding_2 = 64; // tekstialueen yleiset paddingit, kun molemmat sivupalkit näytetään
   var border = 10; // sv-widgetin kehyksen leveys
   var widgetPadding = 25;
   var minus;
   var leveys;
   var width;

    <?php if ($column_left && $column_right) { ?>
           if(screenWidth == 1263){
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1280){
           	  width = screenWidth-paddings;
           	  padding_1 = 20;
           	  widgetPadding = 40;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1152){
           	  paddings = 100;
           	  column_padding = 43;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1135 || screenWidth == 1125){
           	  paddings = 100;
           	  column_padding = 36;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1080){
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1024){
           	  paddings = 30;
           	  column_padding = 16;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 1007){
           	  paddings = 20;
           	  column_padding = 12;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 800 || screenWidth == 797){
           	  paddings = 30;
              widgetPadding = 10;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 768){
           	  paddings = 30;
           	  column_padding = 13;
              widgetPadding = 10;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/2-minus;
           }
           if(screenWidth == 720 || screenWidth == 640){
           	  paddings = 30;
           	  padding_1 = 25;
           	  widgetPadding = 3;
           	  width = screenWidth-paddings;
           	  minus = padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 480){
              paddings = 20;
           	  width = screenWidth-paddings;
           	  padding_1 = 32;
           	  widgetPadding = 5;
           	  column_padding = 10;
           	  border = 0;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1440 || screenWidth == 1680 || screenWidth == 1920 || screenWidth == 2048 || screenWidth == 2160 || screenWidth == 2436 || screenWidth == 2560){
           	  $width = screenWidth-paddings;
           	  minus = paddings+border+widgetPadding+padding_2;
           	  leveys = width/2-minus;
           }
           if(typeof leveys == "undefined"){
            if(screenWidth  < 751){
              // responsive, page is full width
              leveys = 650;
            }
            if(screenWidth  < 700){
              // responsive, page is full width
              leveys = 600;
            }
            if(screenWidth  < 600){
              // responsive, page is full width
              leveys = 480;
            }
            if(screenWidth  < 500){
              // responsive, page is full width
              leveys = 400;
            }
            if(screenWidth  < 400){
              // responsive, page is full width
              leveys = 320;
            }
            if(screenWidth  > 850){
           	  leveys = 310;
            }
            if(screenWidth  > 950){
              leveys = 410;
            }
           }
    <?php } elseif ($column_left || $column_right) { ?>
           if(screenWidth == 1263){
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
            }
           if(screenWidth == 12801){
              paddings = 70;
              padding_1 = 40;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
            }
           if(screenWidth == 1152){
           	  column_padding = 60;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_2+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 1135 || screenWidth == 1125){
           	  column_padding = 50;
           	  padding_1 = 60;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 1080){
           	  padding_1 = 45;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 1024){
           	  paddings = 30;
           	  column_padding = 18;
           	  padding_1 = 36;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 1007){
           	  paddings = 30;
           	  column_padding = 14;
           	  padding_1 = 26;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 800 || screenWidth == 797){
           	  paddings = 25;
           	  padding_1 = 28;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 768){
           	  paddings = 25;
           	  column_padding = 12;
           	  padding_1 = 18;
           	  width = screenWidth-paddings;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width/4*3-minus;
           }
           if(screenWidth == 720 || screenWidth == 640){
           	  paddings = 30;
           	  padding_1 = 25;
           	  widgetPadding = 3;
           	  width = screenWidth-paddings;
           	  minus = padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 480){
              paddings = 20;
           	  width = screenWidth-paddings;
           	  padding_1 = 32;
           	  widgetPadding = 5;
           	  column_padding = 10;
           	  border = 0;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1440 || screenWidth == 1680 || screenWidth == 1920 || screenWidth == 2048 || screenWidth == 2160 || screenWidth == 2436 || screenWidth == 2560){
           	  $width = screenWidth-paddings;
           	  minus = paddings+border+widgetPadding+padding_2;
           	  leveys = width/4*3-minus;
           }
           if(typeof leveys == "undefined"){
            if(screenWidth  < 751){
              // responsive, page is full width
              leveys = 650;
            }
            if(screenWidth  < 700){
              // responsive, page is full width
              leveys = 600;
            }
            if(screenWidth  < 600){
              // responsive, page is full width
              leveys = 480;
            }
            if(screenWidth  < 500){
              // responsive, page is full width
              leveys = 400;
            }
            if(screenWidth  < 400){
              // responsive, page is full width
              leveys = 320;
            }
            if(screenWidth  > 850){
              leveys = 500;
            }
            if(screenWidth  > 974){
              leveys = 650;
            }
            if(screenWidth  > 1182){
              leveys = 810;
            }
           }
    <?php } else { ?>
           if(screenWidth == 1263){
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1280){
           	  width = screenWidth-paddings;
           	  padding_2 = 84;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
            }
           if(screenWidth == 1152 || screenWidth == 1135 || screenWidth == 1125){
           	  paddings = 100;
           	  padding_2 = 115;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1080){
           	  widgetPadding = 42;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1024){
           	 // paddings = 10;
           	  padding_2 = 25;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1007){
           	  paddings = 10;
           	  padding_2 = 61;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 800 || screenWidth == 797){
           	  paddings = 30;
           	  padding_2 = 55;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 768){
              paddings = 10;
           	  padding_2 = 40;
           	  width = screenWidth-paddings;
           	  minus = padding_2+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 720 || screenWidth == 640){
           	  paddings = 30;
           	  padding_1 = 25;
           	  widgetPadding = 3;
           	  width = screenWidth-paddings;
           	  minus = padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 480){
              paddings = 20;
           	  width = screenWidth-paddings;
           	  padding_1 = 32;
           	  widgetPadding = 5;
           	  column_padding = 10;
           	  border = 0;
           	  minus = column_padding+padding_1+border+widgetPadding;

              leveys = width-minus;
           }
           if(screenWidth == 1440 || screenWidth == 1680 || screenWidth == 1920 || screenWidth == 2048 || screenWidth == 2160 || screenWidth == 2436 || screenWidth == 2560){
           	  $width = screenWidth-paddings;
           	  minus = paddings+border+widgetPadding+padding_2;
           	  leveys = width-minus;
           }
           if(typeof leveys == "undefined"){
           	 if(screenWidth > 1280){
           	  leveys = 1110;
           	 }
           	 if(screenWidth > 1600){
           	  leveys = 1410;
           	 }
           	 if(screenWidth < 1280){
           	  leveys = 900;
           	 }
           	 if(screenWidth < 1000){
           	  leveys = 500;
           	 }
           	 if(screenWidth < 700){
           	  leveys = 400;
           	 }
           }
    <?php } ?>
     SV.widget.initWithForm('paytrail', {charset:'UTF-8',width:leveys});
</script>
