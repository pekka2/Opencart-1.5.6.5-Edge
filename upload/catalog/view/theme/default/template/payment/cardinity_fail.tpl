<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $heading_title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/template/payment/cardinity/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/template/payment/cardinity/cardinity.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
<div class="container main">
	<div class="row header">
  		<div class="col-md-12">
  			<img src="catalog/view/theme/default/template/payment/cardinity/logo.png" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/mastercard.jpg" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/maestro.jpg" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/visa.jpg" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/visa_secure.png" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/mastercard_secure.png" alt="" height="40">
			<img src="catalog/view/theme/default/template/payment/cardinity/pci.jpg" alt="" height="40">
  		</div>
	</div>
	<div class="row">
		<?php if ($error_warning) { ?>
 		<div class="alert alert-danger" role="alert"><?php echo $error_warning; ?></div>
 		<?php } ?>
	</div>
	<div class="row">
		<a href="<?php echo $back_to_shop_url; ?>" class="btn btn-primary pull-right"><?php echo $button_back_to_shop; ?></a>
	</div>
</div>
</body>
</html>