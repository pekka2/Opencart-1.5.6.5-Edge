<?php if ($eway_error == 'SUCCESS') { ?>
<div class="success">Refunded.</div>
<?php } elseif ($eway_error != '') { ?>
<div class="warning">Refunded Failed: <?php echo $eway_error; ?></div>
<?php } ?>

<div class="box">
    <div class="heading">
        <h3>Refund</h3>
    </div>
    <div class="content">
        <form action="<?php echo $refund_link; ?>" method="post" id="eway_refund_form" onsubmit="return avoidDuplicationSubmit()">
            <table class="form">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
                <tr>
                    <td>Refund Amount:</td>
                    <td>
                        <input type="text" name="refund_amount" value="" />
                        <p>
                            Total: <?php echo $eway_order['amount'] ?>
                            <?php
                                if ($eway_order['refund_amount'] > 0) {
                                    echo "Already Refund: " . $eway_order['refund_amount'];
                                }
                            ?>
                        </p>
                    </td>
                </tr>
                <tr><td>&nbsp;</td><td><input type='submit' value='Refund' /></td></tr>
            </table>
        </form>
    </div>
</div>

<script language="JavaScript" type="text/javascript" >
//<!--
  var submitcount = 0;
  function avoidDuplicationSubmit(){
    if (submitcount == 0) {
      // sumbit form
      submitcount++;
      return true;
    } else {
      alert("Transaction is in progress.");
      return false;
    }
  }
//-->
</script>