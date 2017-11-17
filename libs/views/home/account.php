<div class="wrapper clearfix wrapper-account">
	<?php
	include "menu.php";
	include "container.php";
	include "account".ucfirst($contain)."View.php";
	include "template.php";
	?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>
<script src="<?php echo ROOT_PATH;?>/static/js/account.js" id="alone-js"></script>