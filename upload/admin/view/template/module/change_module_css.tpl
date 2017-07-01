<?php echo $header; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-slideshow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slideshow" class="form-horizontal">
           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css_status" id="input-status" class="form-control">
                <?php if ($change_module_css_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> 
<?php
if( $modules ){
           $bestseller =$modules['bestseller']; 
           $featured =$modules['featured']; 
           $latest =$modules['latest'];      
           $special =$modules['special'];
 }
?>  
          <div class="panel-heading"><h3><?php echo $text_bestseller;?></h3></div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_lg; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[bestseller][lg]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($bestseller['lg']) && $i == $bestseller['lg']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php if(isset($bestseller['lg']) ) { echo $bestseller['lg']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">md-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
         
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_md; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[bestseller][md]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($bestseller['md']) && $i == $bestseller['md']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">md-<?php if(isset($bestseller['md']) ){echo $bestseller['md']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">md-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_caption_height; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[bestseller][sy]" id="input-status" class="form-control">
              <?php for($i = 0;$i< count($cssy['value']); $i++){
                       if(isset($bestseller['sy']) && $cssy['value'][$i] == $bestseller['sy']) {?> 
              <option value="<?php echo $cssy['value'][$i]; ?>" selected="selected"><?php echo $cssy['value'][$i] . ' ( '. $cssy['info'][$i] . ' )'; ?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $cssy['value'][$i]; ?>"><?php echo $cssy['value'][$i] . ' ( '.$cssy['info'][$i] . ' )'; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_tax_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[bestseller][tax_status]" id="input-status" class="form-control">
                <?php if (isset($bestseller['tax_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_description_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[bestseller][desc_status]" id="input-status" class="form-control">
                <?php if (isset($bestseller['desc_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_strlen; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[bestseller][css_strlen]" id="input-status" placeholder="25" value="<?php if (isset($bestseller['css_strlen'])){ echo $bestseller['css_strlen'];}?>" class="form-control">
            </div>
          </div>
          
          
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_product_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[bestseller][css_productname]" id="input-status" placeholder="25" value="<?php if (isset($bestseller['css_productname'])){ echo $bestseller['css_productname'];}?>" class="form-control">
            </div>
          </div>
          <!----------- ------------------------------------ -->
            <div class="panel-heading"><h3><?php echo $text_featured;?></h3></div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_lg; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[featured][lg]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($featured['lg']) && $i == $featured['lg']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  if(isset($featured['lg'])) { echo $featured['lg']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">lg-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
         
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_md; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[featured][md]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($featured['md']) && $i == $featured['md']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">md-<?php  if(isset($featured['md'])){ echo $featured['md']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">md-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_caption_height; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[featured][sy]" id="input-status" class="form-control">
              <?php for($i = 0;$i< count($cssy['value']); $i++){
                       if(isset($featured['sy']) && $cssy['value'][$i] == $featured['sy']) {?> 
              <option value="<?php echo $cssy['value'][$i]; ?>" selected="selected"><?php echo $cssy['value'][$i] . ' ( '. $cssy['info'][$i] . ' )'; ?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $cssy['value'][$i]; ?>"><?php echo $cssy['value'][$i] . ' ( '.$cssy['info'][$i] . ' )'; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_tax_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[featured][tax_status]" id="input-status" class="form-control">
                <?php if (isset($featured['tax_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_description_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[featured][desc_status]" id="input-status" class="form-control">
                <?php if (isset($featured['desc_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_strlen; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[featured][css_strlen]" id="input-status" placeholder="25" value="<?php if (isset($featured['css_strlen'])){ echo $featured['css_strlen'];}?>" class="form-control">
            </div>
          </div>
          
          
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_product_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[featured][css_productname]" id="input-status" placeholder="25" value="<?php if (isset($featured['css_productname'])){ echo $featured['css_productname'];}?>" class="form-control">
            </div>
          </div>
          <!----------- ------------------------------------ -->
            <div class="panel-heading"><h3><?php echo $text_latest;?></h3></div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_lg; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[latest][lg]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if( isset($latest['md']) && $i == $latest['lg']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php if( isset($latest['md']) ) { echo $latest['lg']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">lg-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
         
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_md; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[latest][md]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($latest['md']) && $i == $latest['md']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">md-<?php  if(isset($latest['md']) ) { echo $latest['md']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">md-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_caption_height; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[latest][sy]" id="input-status" class="form-control">
              <?php for($i = 0;$i< count($cssy['value']); $i++){
                       if(isset($latest['sy']) && $cssy['value'][$i] == $latest['sy']) {?> 
              <option value="<?php echo $cssy['value'][$i]; ?>" selected="selected"><?php echo $cssy['value'][$i] . ' ( '. $cssy['info'][$i] . ' )'; ?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $cssy['value'][$i]; ?>"><?php echo $cssy['value'][$i] . ' ( '.$cssy['info'][$i] . ' )'; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_tax_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[latest][tax_status]" id="input-status" class="form-control">
                <?php if (isset($latest['tax_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_description_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[latest][desc_status]" id="input-status" class="form-control">
                <?php if (isset($latest['desc_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_strlen; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[latest][css_strlen]" id="input-status" placeholder="25" value="<?php if (isset($latest['css_strlen'])){ echo $latest['css_strlen'];}?>" class="form-control">
            </div>
          </div>
          
          
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_product_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[latest][css_productname]" id="input-status" placeholder="25" value="<?php if (isset($bestseller['css_productname'])){ echo $latest['css_productname'];}?>" class="form-control">
            </div>
          </div>
          <!----------- ------------------------------------ -->
            <div class="panel-heading"><h3><?php echo $text_special;?></h3></div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_lg; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[special][lg]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($special['lg']) && $i == $special['lg']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  if(isset($special['lg'])) { echo $special['lg'];} ?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">lg-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
         
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_md; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[special][md]" id="input-status" class="form-control">
              <?php for($i = 1;$i<=12; $i++){
                       if(isset($special['md']) && $i == $special['md']) {?> 
              <option value="<?php echo $i; ?>" selected="selected">md-<?php  if(isset($special['md']) ) { echo $special['md']; }?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $i; ?>">md-<?php echo $i; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_caption_height; ?></label>
            <div class="col-sm-10">
        
              <select name="change_module_css[special][sy]" id="input-status" class="form-control">
              <?php for($i = 0;$i< count($cssy['value']); $i++){
                       if(isset($special['sy']) && $cssy['value'][$i] == $special['sy']) {?> 
              <option value="<?php echo $cssy['value'][$i]; ?>" selected="selected"><?php echo $cssy['value'][$i] . ' ( '. $cssy['info'][$i] . ' )'; ?></option>
              <?php }elseif($i == 3){?> 
              <option value="<?php echo $i; ?>" selected="selected">lg-<?php  echo $i; ?></option>
              <?php } else{?> 
              <option value="<?php echo $cssy['value'][$i]; ?>"><?php echo $cssy['value'][$i] . ' ( '.$cssy['info'][$i] . ' )'; ?></option>
              <?php } ?>
      <?php } ?>
              </select>
              
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_tax_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[special][tax_status]" id="input-status" class="form-control">
                <?php if (isset($special['tax_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_description_status; ?></label>
            <div class="col-sm-10">
              <select name="change_module_css[special][desc_status]" id="input-status" class="form-control">
                <?php if (isset($special['desc_status'])) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_strlen; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[special][css_strlen]" id="input-status" placeholder="25" value="<?php if (isset($special['css_strlen'])){ echo $special['css_strlen'];}?>" class="form-control">
            </div>
          </div>
          
          
                  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_product_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="change_module_css[special][css_productname]" id="input-status" placeholder="25" value="<?php if (isset($special['css_productname'])){ echo $special['css_productname'];}?>" class="form-control">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>