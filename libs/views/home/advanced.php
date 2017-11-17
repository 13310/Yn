<div class="wrapper clearfix">
	<?php include( "menu.php" ) ?>
	<?php include( "container.php" ) ?>
	<div class="view">
		<div class="view-header clearfix">
			<img class="login-userlogo" src="<?php echo ROOT_PATH;?>/static/img/logo.jpg"/>
			<div class="view-more clearfix"><span></span></div>
			<span class="view-hr"></span>
			<span class="login-username">姓名</span>
		</div>
		<div class="view-menu"></div>
		<div class="view-main">
			<?php print_r($_SERVER)?>
		</div>
	</div>
	<?php include( "template.php" ) ?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>