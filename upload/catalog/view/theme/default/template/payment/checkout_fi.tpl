<fieldset id="payment">    
<div id="checkout-fi-box"></div>
<div class="buttons">
  <div class="pull-right">
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-complete" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
  </div>
  </fieldset>
<script type="text/javascript"><!--
$('#checkout-fi-box').hide();
$('#button-complete').click(function() {
    $.ajax({
        url: 'index.php?route=payment/checkout_fi/complete',
        dataType: 'html',
        success: function(json) {
           $('#checkout-fi-box').show();
    
              $('#checkout-fi-box').css({"position":"absolute"});
           if(json.indexOf('Error',5) > 0 || json.indexOf('Ota yhteyttä',5) > 0){
              $('#checkout-fi-box').css({"width":"100%"});
              $('#checkout-fi-box').css({"height":"40px"});
              $('#checkout-fi-box').css({"margin-bottom":"10px"});
              $('#checkout-fi-box').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle" style="position:relative;float:left;padding-right:10px;margin-top:3px;"></i>'+ json + '<button type="button" class="close"  style="padding:0;margin-top:-1px;" data-dismiss="alert">&times;</button></div>');

              $('.alert-danger p').css({"display":"inline"});
              $('.alert-danger a').css({"font-weight":"bold"});
              $('.alert-danger a').css({"margin-left":"20px"});
           }
           if(!json.indexOf('Error',5) == 0 && !json.indexOf('Ota yhteyttä',5) == 0){
              $('#checkout-fi-box').css({"width":"80%"});
              $('#checkout-fi-box').css({"height":"auto"});
              $('#checkout-fi-box').css({"margin-left":"10%"});
              $('#checkout-fi-box').css({"top":"0"});
              $('#checkout-fi-box').css({"z-index":"1000"});
              $('#checkout-fi-box').html(json);
              $('#checkout-fi-box').css({"padding":"25px"});
              $('#checkout-fi-box').css({"background":"#ffffff"});

              $('#button-complete').hide();
           }
           $('#checkout-fi-box').css({"border":"solid 1px #666666"});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
//--></script>