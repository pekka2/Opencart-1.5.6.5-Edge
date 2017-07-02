<?php
    if($position == 'column_left' || $position == 'column_right'){
                           $class = 'class="col-lg-12 col-md-12 col-sm-12 col-xs-12"';
                           $cssy = '';
                           $column = true;
                       $padding = '';
    }
    if($position == 'content_top' || $position == 'content_bottom'){
                       if($change){
                                              $lg = $change['lg'];
                                              $md = $change['md'];
                                              $sy = $change['sy'];
                                              if($sidebar_info['left'] && $sidebar_info['right'] && $lg == 3){
                                                                     $padding = ' style="padding:0px 10px;"';
                                              } else{
                                                                     $padding = '';
                                              }
                       } else {
                                              $lg = 4;
                                              $md = 4;
                                              $sy = 'sy-04';
                                              $padding = '';
                    }
                           $class = 'class="col-lg-' . $lg . ' col-md-' . $md . ' col-sm-6 col-xs-12"';
                           $cssy = ' ' . $sy;
    }
?>
<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div <?php echo $class;?>>
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption<?php echo $cssy;?>"<?php echo $padding;?>>
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php  $length = strlen($product['description']);

                  if($status == false){
                                         $max = 100;
                  }
                  if($length > $max){
                                         $c = $max;
                  }else{
                                         $c = $length;
                  }
              
            if($description_status || $status == false){         
                   for($i=0; $i<$c;$i++){
                                          echo $product['description'][$i];
                   }
          }
                   ?></p>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
          <?php if( $tax_status || $status == false) { ?><span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span><?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
