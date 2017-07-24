<?php if (isset($error)) { ?>
    <div class="warning"><?php echo $error; ?></div>
<?php } else { ?>
    <form action="<?php echo $action; ?>" method="post" id="eway-payment-form">
        <input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $AccessCode; ?>" />

        <?php if (isset($text_testing)) { ?>
            <div class="warning"><?php echo $text_testing; ?></div>
        <?php } ?>

        <div style="margin-bottom: 10px;">
            <?php
            if (count($payment_type) == 0)
                $payment_type = array('visa', 'mastercard', 'jcb', 'diners', 'amex', 'paypal', 'masterpass');
            if (count($payment_type) == 1) {
                echo "<input type='hidden' name='EWAY_PAYMENTTYPE' value='" . $payment_type[0] . "' />";
            } else {
                if (in_array('visa', $payment_type) || in_array('mastercard', $payment_type) || in_array('diners', $payment_type) || in_array('jcb', $payment_type) || in_array('amex', $payment_type)) {
                    echo "<label><input type='radio' name='EWAY_PAYMENTTYPE' id='eway_radio_cc' value='creditcard' checked='checked' onchange='javascript:select_eWAYPaymentOption(\"creditcard\")' /> ";
                    if (in_array('visa', $payment_type)) {
                        echo '<img src="catalog/view/theme/default/image/eway_creditcard_visa.png" height="30" alt="Visa" /> ';
                    }
                    if (in_array('mastercard', $payment_type)) {
                        echo '<img src="catalog/view/theme/default/image/eway_creditcard_master.png" height="30" alt="MasterCard" /> ';
                    }
                    if (in_array('diners', $payment_type)) {
                        echo '<img src="catalog/view/theme/default/image/eway_creditcard_diners.png" height="30" alt="Diners Club" /> ';
                    }
                    if (in_array('jcb', $payment_type)) {
                        echo '<img src="catalog/view/theme/default/image/eway_creditcard_jcb.png" height="30" alt="JCB" /> ';
                    }
                    if (in_array('amex', $payment_type)) {
                        echo '<img src="catalog/view/theme/default/image/eway_creditcard_amex.png" height="30" alt="AMEX" /> ';
                    }
                    echo '</label> ';
                }
                if (in_array('paypal', $payment_type)) {
                    echo "<label><input type='radio' name='EWAY_PAYMENTTYPE' value='paypal' onchange='javascript:select_eWAYPaymentOption(\"paypal\")' />".'<img src="catalog/view/theme/default/image/eway_paypal.png" height="30" alt="PayPal" /></label>' ;
                }
                if (in_array('masterpass', $payment_type)) {
                    echo "<label><input type='radio' name='EWAY_PAYMENTTYPE' value='masterpass' onchange='javascript:select_eWAYPaymentOption(\"masterpass\")' />".'<img src="catalog/view/theme/default/image/eway_masterpass.png" height="30" alt="MasterPass by MasterCard" /></label>' ;
                }
		/*
                if (in_array('vme', $payment_type)) {
                    echo "<label><input type='radio' name='EWAY_PAYMENTTYPE' value='vme' onchange='javascript:select_eWAYPaymentOption(\"vme\")' /> <img src='catalog/view/theme/default/image/eway_vme.png' height='30' /></label> ";
                }
		*/
            }
            ?>

        </div>

        <?php
        if (in_array('paypal', $payment_type)) {
            echo '<p id="tip_paypal" style="display:none;">After you click "Confirm Order" you will be redirected to "PayPal" to complete your payment.</p>';
        }
        if (in_array('masterpass', $payment_type)) {
            echo '<p id="tip_masterpass" style="display:none;">After you click "Confirm Order" you will be redirected to "MasterPass by MasterCard" to complete your payment.</p>';
        }
	/*
        if (in_array('vme', $payment_type)) {
            echo '<p id="tip_vme" style="display:none;">After you click "Confirm Order" you will be redirected to "V.Me by Visa" to complete your payment.</p>';
        }
	*/
        ?>

    <?php if (in_array('visa', $payment_type) || in_array('mastercard', $payment_type) || in_array('diners', $payment_type) || in_array('jcb', $payment_type) || in_array('amex', $payment_type)) { ?>
            <div class="content" id="creditcard_info">
                <font size="2pt"><strong>Credit Card Payment</strong></font>
                <table id="eway_table" cellspacing="0" cellpadding="3" border="0">
                    <tr>
                        <td>
                            <span id="Label10"><span class="required">*</span> Card Holder's Name:</span></td>
                        <td>
                            <input name="EWAY_CARDNAME" type="text" id="EWAY_CARDNAME" autocomplete="off" />
                            <span id="ewaycard_error"></span>
                        </td></tr>
                    <tr>
                        <td>
                            <span id="Label2"><span class="required">*</span> Card Number:</span></td>
                        <td>
                            <input name="EWAY_CARDNUMBER" type="text" maxlength="17" id="EWAY_CARDNUMBER" autocomplete="off" pattern="\d*" />
                            <span id="ewaynumber_error"></span>
                        </td></tr>
                    <tr>
                        <td>
                            <span id="Label3"><span class="required">*</span> Card Expiry:</span></td>
                        <td>
                            <select name="EWAY_CARDEXPIRYMONTH" id="EWAY_CARDEXPIRYMONTH">
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <select name="EWAY_CARDEXPIRYYEAR" id="EWAY_CARDEXPIRYYEAR">

                                <?php
                                $start = date('Y');
                                $end = $start + 7;
                                for ($i = $start; $i <= $end; $i++) {
                                    $j = $i - 2000;
                                    echo "<option value='$j'>$i</option>";
                                }
                                ?>

                            </select> month / year <div id="expiry_error"></div></td></tr>
                    <tr>
                        <td>
                            <span id="Label2"><span class="required">*</span> CVV/CSV Number:</span></td>
                        <td>
                            <input name="EWAY_CARDCVN" type="text" maxlength="4" id="EWAY_CARDCVN" size="5" autocomplete="off" pattern="\d*" />
                            <span id="cvn_details" class="help">
                                For MasterCard or Visa, this is the last three digits in the signature area on the back of your card.
                                <?php
                                if (in_array('amex', $payment_type)) {
                                    echo "<br>For American Express, it's the four digits on the front of the card.";
                                }
                                ?>
                            </span>
                            <span id="ewaycvn_error"></span>
                        </td></tr>

                </table>
            </div>
    <?php } ?>

    </form>

    <div class="buttons">
        <div class="right">
	    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
	</div>
    </div>
<script language="JavaScript" type="text/javascript" >
    //<!--
    function select_eWAYPaymentOption(v) {
	if (document.getElementById("creditcard_info"))
	    document.getElementById("creditcard_info").style.display = "none";
	if (document.getElementById("tip_paypal"))
	    document.getElementById("tip_paypal").style.display = "none";
	if (document.getElementById("tip_masterpass"))
	    document.getElementById("tip_masterpass").style.display = "none";
	if (document.getElementById("tip_vme"))
	    document.getElementById("tip_vme").style.display = "none";
	if (v == 'creditcard') {
	    document.getElementById("creditcard_info").style.display = "block";
	} else {
	    document.getElementById("tip_" + v).style.display = "block";
	}
    }

    $('#button-confirm').bind('click', function() {

	if ($('#eway_radio_cc').is(':checked')) {
	    var eway_error = false;
	    if ($('#EWAY_CARDNAME').val().length < 1) {
		eway_error = true;
		$('#ewaycard_error').html('<span class="error">Card Holder\'s Name must be entered</span>');
	    } else {
		$('#ewaycard_error').empty();
	    }

	    var ccnum_regex = new RegExp("^[0-9]{13,19}$");
	    if (!ccnum_regex.test($('#EWAY_CARDNUMBER').val().replace(/ /g, '')) || !luhn10($('#EWAY_CARDNUMBER').val())) {
		eway_error = true;
		$('#ewaynumber_error').html('<span class="error">Card Number appears invalid</span>');
	    } else {
		$('#ewaynumber_error').empty();
	    }

	    var cc_year = parseInt($('#EWAY_CARDEXPIRYYEAR').val(),10) + 2000;
	    var cc_month = parseInt($('#EWAY_CARDEXPIRYMONTH').val(),10);

	    var cc_expiry = new Date(cc_year, cc_month, 1);
	    var cc_expired = new Date(cc_expiry - 1);
	    var today = new Date();

	    if (today.getTime() > cc_expired.getTime()) {
		eway_error = true;
		$('#expiry_error').html('<span class="error">This expiry date has passed</span>');
	    } else {
		$('#expiry_error').empty();
	    }

	    var ccv_regex = new RegExp("^[0-9]{3,4}$");
	    if (!ccv_regex.test($('#EWAY_CARDCVN').val().replace(/ /g, ''))) {
		eway_error = true;
		$('#ewaycvn_error').html('<span class="error">CVV/CSV Number appears invalid</span>');
	    } else {
		$('#ewaycvn_error').empty();
	    }

	    if (eway_error) {
		return false;
	    }
	}

	$('#eway-payment-form').submit();
	$("#button-confirm").prop('disabled', true);

    });


    var luhn10 = function(a,b,c,d,e) {
	for(d = +a[b = a.length-1], e=0; b--;)
	    c = +a[b], d += ++e % 2 ? 2 * c % 10 + (c > 4) : c;
	return !(d%10)
    };
//-->
</script>
<?php } ?>
