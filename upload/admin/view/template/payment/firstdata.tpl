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
          <a href="#tab-account"><?php echo $tab_account; ?></a>
          <a href="#tab-order-status"><?php echo $tab_order_status; ?></a>
          <a href="#tab-payment"><?php echo $tab_payment; ?></a>
          <a href="#tab-advanced"><?php echo $tab_advanced; ?></a>
      </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div id="tab-account">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
                <td>  <input type="text" name="firstdata_merchant_id" value="<?php echo $firstdata_merchant_id; ?>" size="100"/>
                  <?php if ($error_merchant_id) { ?>
                  <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td>
                  <input type="password" name="firstdata_secret" value="<?php echo $firstdata_secret; ?>" size="100"/>
                  <?php if ($error_secret) { ?>
                  <div class="text-danger"><?php echo $error_secret; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_live_demo; ?></td>
                <td>
                  <select name="firstdata_live_demo" id="input-live-demo" class="form-control">
                    <?php if ($firstdata_live_demo) { ?>
                    <option value="1" selected="selected"><?php echo $text_live; ?></option>
                    <option value="0"><?php echo $text_demo; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_live; ?></option>
                    <option value="0" selected="selected"><?php echo $text_demo; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="firstdata_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $firstdata_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_debug; ?></td>
            <td><select name="firstdata_debug">
                <?php if ($firstdata-debug) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="firstdata_status">
                <?php if ($firstdata_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td>
                  <input type="text" name="firstdata_total" value="<?php echo $firstdata_total; ?>" size="100"/></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order; ?></td>
                <td>
                  <input type="text" name="firstdata_sort_order" value="<?php echo $firstdata_sort_order; ?>" size="20"/></td>
              </tr>
            </table>
    </div>
    <div id="tab-order-status">
        <table class="form">
          <tr>
            <td><?php echo $entry_status_success_settled; ?></td>
            <td><select name="firstdata_order_status_success_settled">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_success_settled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status_success_unsettled; ?></td>
            <td><select name="firstdata_order_status_success_unsettled_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_success_unsettled_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status_decline; ?></td>
            <td><select name="firstdata_order_status_decline_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_decline_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status_void; ?></td>
            <td><select name="firstdata_order_status_void_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $firstdata_order_status_void_id) { ?>
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
            <td><select name="firstdata_auto_settle">
                    <option value="0"
                <?php echo ($firstdata_auto_settle == 0 ? ' selected' : ''); ?>><?php echo $text_settle_delayed; ?></option>
                    <option value="1"
                <?php echo ($firstdata_auto_settle == 1 ? ' selected' : ''); ?>><?php echo $text_settle_auto; ?></option>
                  </select>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_enable_card_store; ?></td>
            <td><select name="firstdata_card_storage">
                    <?php if ($firstdata_card_storage) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
              </select></td>
          </tr>
          </table>
    </div>      
    <div id="tab-advanced">
            <table class="form">
              <tr>
                <td><?php echo $entry_live_url; ?></td>
                <td>
                  <input type="text" name="firstdata_live_url" value="<?php echo $firstdata_live_url; ?>" size="100"/>
                  <?php if ($error_live_url) { ?>
                  <div class="text-danger"><?php echo $error_live_url; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_demo_url; ?></td>
                <td>
                  <input type="text" name="firstdata_demo_url" value="<?php echo $firstdata_live_url; ?>" size="100"/>
                  <?php if ($error_demo_url) { ?>
                  <div class="text-danger"><?php echo $error_demo_url; ?></div>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_notification_url; ?></td>
                <td>
                  <input type="text" value="<?php echo $notify_url; ?>" size="100"/></td>
              </tr>
              </table>
           </div>
        </form>
      </div>
  </div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> </div>
<?php echo $footer; ?>