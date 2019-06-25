 		<div class="row">
           <?php if ($column_left && $column_right) { ?>
           <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
               <?php $class = 'col-sm-9'; ?>
               <?php } else { ?>
             <?php $class = 'col-sm-12'; ?>
            <?php } ?>
				<div id="contwnt" class="<?php echo $class; ?>">
				<?php if($payment_form){?>
					<h2><?php echo $text_card_payment;?></h2>

					<form id="card-form" action="#" role="form" autocomplete="off">
						<div class="form-group">
							<label class="col-sm-2" for="cardNumber"><?php echo $entry_card_number;?></label>
                            <div class="col-sm-9">
							<input type="number" id="cardNumber" placeholder="<?php echo $help_card_number;?>" class="form-control"/>
						</div>
						</div>
						<div class="row">
							<div class="<?php echo $class; ?>">
								<div class="form-group">
									<label for="expMonth"><?php echo $entry_month;?></label></label>
									<select id="expMonth" class="form-control">
										<?php
										for($i = 1; $i <= 12; $i++)
											echo "<option>".str_pad($i,2,'0',STR_PAD_LEFT)."</option>";
										?>
									</select>
								</div>
							</div>
							
           <?php if ($column_left && $column_right) { ?>
           <?php $class = 'col-sm-6 col-xs-12'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
               <?php $class = 'col-sm-6 col-xs-12'; ?>
               <?php } else { ?>
             <?php $class = 'col-sm-12 col-xs-12'; ?>
            <?php } ?>
							<div class="<?php echo $class; ?>">
								<div class="form-group">
									<label for="expYear"><?php echo $entry_year;?></label>
									<select id="expYear" class="form-control">
									<?php
									$i = $j = date("Y");
									while($i <= $j + 5)
										echo "<option>".$i++."</option>";
									?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="<?php echo $class; ?>">
								<div class="form-group">
									<label for="cvv"><?php echo $entry_cvv;?></label>
									<input type="number" id="cvv" maxlength="4" class="form-control" lenght="4" placeholder="<?php echo $help_cvv;?>"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="<?php echo $class; ?>">
								<div class="form-group">
									<input type="submit" id="card-pay" class="btn btn-primary" value="<?php echo $button_pay;?>"/>
								</div>
							</div>
						</div>
					</form>
					<div class="card-payment-result text-muted"></div>
					<?php }?>
					<hr>
					<h2><?php echo $text_pay_button;?></h2>
					<?php foreach ($merchantPaymentMethods->payment_methods as $pm): ?>
						<a class="img" href="<?php echo $auth;?>&selected=<?php echo $pm->selected_value ?>">
							<img alt="<?php echo $pm->name ?>" src="<?php echo $pm->img ?>">
						</a>
					<?php endforeach; ?>
					<hr>
				</div>
			</div>

   <?php if($payment_form){?>	
	<script>
		var card_payment_result = $('.card-payment-result')

		$('#card-form').submit(function(e) {
			e.preventDefault()

			card_payment_result.html('')
			card_payment_result.append('<?php echo $text_token;?>')

			var chargeRequest = $.get("<?php echo $card;?>")

			chargeRequest.done(function(data) {

				card_payment_result.append('<br><?php echo $text_charging;?>')

				var response

				try
				{
					response = $.parseJSON(data)
				}
				catch(err)
				{
					card_payment_result.html('<?php echo $error_create_payment;?>')
					alert('<?php echo $error_create_payment;?>')
					return
				}

				var charge = $.post(response.url, {
					token: response.token,
					amount: <?php echo $amount;?>,
					card: $('#cardNumber').val(),
					security_code: $('#cvv').val(),
					currency: 'EUR',
					exp_month: $('#expMonth').val(),
					exp_year: $('#expYear').val()
				})

				charge.done(function(result) {	
					card_payment_result.append('<br><?php echo $text_checking;?>')
					var complete = $.get('<?php echo $action;?>' + response.token)

					complete.done(function(data) {
						var msg = (data === 'success') ? '<strong class="text-success"><?php echo $text_success;?></strong>' : '<strong class="text-danger"><?php echo $text_failed;?></strong>'
						card_payment_result.append('<br>' + msg)
					})
				})
			})
		})
	</script>
	<?php }?>
