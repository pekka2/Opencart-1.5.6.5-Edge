<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
         <a href="#tab-account" data-toggle="tab"><?php echo $tab_account; ?></a>
         <a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a>
         <a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-account">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
                <td>  <input type="text" name="firstdata_remote_merchant_id" value="<?php echo $firstdata_remote_merchant_id; ?>" size="100"/>
                  <?php if ($error_merchant_id) { ?>
                  <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_user_id; ?></td>
                <td>
                  <input type="text" name="firstdata_remote_user_id" value="<?php echo $firstdata_remote_user_id; ?>" size="100"/>
                  <?php if ($error_user_id) { ?>
                  <div class="text-danger"><?php echo $error_user_id; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_password; ?></td>
                  <td><input type="text" name="firstdata_remote_password" value="<?php echo $firstdata_remote_password; ?>" size="100"/>
                  <?php if ($error_password) { ?>
                  <div class="text-danger"><?php echo $error_password; ?></div>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_certificate_path; ?></td>
                 <td> <input type="text" name="firstdata_remote_certificate" value="<?php echo $firstdata_remote_certificate; ?>" size="100"/>
                  <?php if ($error_certificate) { ?>
                  <div class="text-danger"><?php echo $error_certificate; ?></div>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_certificate_key_path; ?></td>
                 <td> <input type="text" name="firstdata_remote_key" value="<?php echo $firstdata_remote_key; ?>" size="100"/>
                  <?php if ($error_key) { ?>
                  <div class="text-danger"><?php echo $error_key; ?></div>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_certificate_key_pw; ?></td>
                  <td><input type="text" name="firstdata_remote_key_pw" value="<?php echo $firstdata_remote_key_pw; ?>" size="100"/>
                  <?php if ($error_key_pw) { ?>
                  <div class="text-danger"><?php echo $error_key_pw; ?></div>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_certificate_ca_path; ?></td>
                 <td> <input type="text" name="firstdata_remote_ca" value="<?php echo $firstdata_remote_ca; ?>" size="100"/>
                  <?php if ($error_ca) { ?>
                  <div class="text-danger"><?php echo $error_ca; ?></div>
                  <?php } ?>
                </td>
              </tr>
          <tr>
            <td><?php echo $entry_debug; ?></td>
            <td><select name="firstdata_remote_debug">
                <?php if ($firstdata_remote_debug) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="firstdata_remote_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $firstdata_remote_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td>
                  <input type="text" name="firstdata_remote_total" value="<?php echo $firstdata_remote_total; ?>" size="100"/></td>
              </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="firstdata_remote_status">
                <?php if ($firstdata_remote_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
              <tr>
                <td><?php echo $entry_sort_order; ?></td>
                <td>
                  <input type="text" name="firstdata_remote_sort_order" value="<?php echo $firstdata_remote_sort_order; ?>" size="20"/></td>
              </tr>
            </table>
   </div>
   <div id="tab-order-status">
          <table class="form">
          <tr>
            <td><?php echo $entry_status_success_settled; ?></td>
            <td><select name="firstdata_remote_order_status_success_settled">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_remote_order_status_success_settled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status_success_unsettled; ?></td>
            <td><select name="firstdata_remote_order_status_success_unsettled_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_remote_order_status_success_unsettled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status_decline; ?></td>
            <td><select name="firstdata_remote_order_status_decline_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_remote_order_status_decline_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
       </table>
  </div>   
  <div id="tab-payment">
            <table class="form">
          <tr>
            <td><?php echo $entry_auto_settle; ?></td>
            <td><select name="firstdata_remote_auto_settle">
                <?php if ($firstdata_remote_auto_settle) { ?>
                <option value="1"><?php echo $text_settle_auto; ?></option>
                <option value="0" selected="selected"><?php echo $text_settle_delayed; ?></option>
                <?php } else { ?>
                <option value="1" selected="selected"><?php echo $text_settle_auto; ?></option>
                <option value="0"><?php echo $text_settle_delayed; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_enable_card_store; ?></td>
            <td><select name="firstdata_remote_card_storage">
                    <?php if ($firstdata_remote_card_storage) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_cards_accepted; ?></td>
            <td>
                  <?php foreach ($cards as $card) { ?>
              
                      <?php if (in_array($card['value'], $firstdata_remote_cards_accepted)) { ?>
                      <input type="checkbox" name="firstdata_remote_cards_accepted[]" value="<?php echo $card['value']; ?>" checked="checked" />
                      <?php echo $card['text']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="firstdata_remote_cards_accepted[]" value="<?php echo $card['value']; ?>" />
                      <?php echo $card['text']; ?>
                      <?php } ?>
                  <?php } ?>
            </td>
          </tr>
        </table>
      </div>
    </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script>
<?php echo $footer; ?>