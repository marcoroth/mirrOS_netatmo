<p>
	<?php echo _("netatmo_how_to_use"); ?><br /><br />

	<a href="#" ><?php echo _("netatmo_tutorial_create_app"); ?></a><br /><br />

	<?php echo _("netatmo_already_have_app"); ?>
</p>

<h5><?php echo _('netatmo_email');?></h5>
<input type="checkbox" id="email_checked" name="netatmo_email_check" /> <?php echo _("netatmo_use_email"); ?> <br />
<input type="email" id="email" placeholder="<?php echo _('netatmo_email');?>" value="<?php echo $email; ?>"/>

<h5><?php echo _('netatmo_password');?></h5>
<input type="password" id="password" placeholder="<?php echo _('netatmo_password');?>"/>

<h5><?php echo _('netatmo_client_id');?></h5>
<input type="text" id="client_id" placeholder="<?php echo _('netatmo_client_id');?>" value="<?php echo $client_id; ?>"/>

<h5><?php echo _('netatmo_client_secret');?></h5>
<input type="text" id="client_secret" placeholder="<?php echo _('netatmo_client_secret');?>" value="<?php echo $client_secret; ?>"/>

<a href="/modules/netatmo/assets/resetConfig.php"><?php echo _("netatmo_reset_config"); ?></a><br /><br />


<div class="block__add" id="netatmo_save_step1">
	<button class="netatmo_save_step1--button" href="#">
		<span><?php echo _('save'); ?></span>
	</button>
</div>

<script>
	<?php include '_step1.js'; ?>
</script>
