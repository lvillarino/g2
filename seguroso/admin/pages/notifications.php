<div class="wrap">
	<?php add_thickbox(); ?>
	
	<?php
	if ( isset( $_GET ) && $_GET['id'] > 0 ):
		?>
		<?php
		$id = $_GET['id'];
		$noti = G2_NOTIFICATIONS::getOne( $id ); ?>
		<h1><?php echo $noti->module; ?></h1>
		<strong style="color: #0046ff;display: block;">DATE : <?php echo date( 'd F Y H:i:s', $noti->timestamp ); ?></strong>
		<?php
		echo $noti->desc;
	else: ?>
		<h1><?php _e( 'Notifications', 'ldomain' ); ?></h1><?php
		$table = new G2_NOTI_TABLE();
		$table->prepare_items();
		?>
		<form id="" method="get">
			<input type="hidden" name="page" value="g2-security-notifications" placeholder="" class="form-control">
			<?php $table->display(); ?>
		</form>
	<?php endif; ?>
</div>
<style>
	#aTB_ajaxContent, #aTB_window {
		width : 100% !important;
	}
</style>