<div class="wrapper clearfix wrapper-recycle">
	<?php
	include( "menu.php" );
	?>
	<div class="view">
		<?php include "viewHead.php"?>
		<div class="view-main">
			<div class="view-thumbnail">
				<div class="no-c">
					<img src="<?php echo ROOT_PATH?>/static/img/nofile.png" alt="没有文件">
					<p>回收站很干净</p>
				</div>
			</div>
		</div>
	</div>
	<?php include( "template.php" ) ?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>
<script src="<?php echo ROOT_PATH;?>/static/js/recycle.js" id="alone-js"></script>