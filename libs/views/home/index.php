<div class="wrapper clearfix wrapper-home">
	<?php include( "menu.php" ) ?>
	<div class="view">
		<?php include "viewHead.php"?>
		<div class="view-main">
			<div class="user-avatar">
				<img src="<?php echo ROOT_PATH;?>/static/img/logo.jpg" alt="头像">
			</div>
			<div class="user-name">你好! <?php echo $UserName;?></div>
			<div class="user-create">账户创建于:<?php echo $CreateTime;?></div>
			<div class="user-info clearfix">
				<div class="together pull-left">
					<h2>空间使用比: </h2>
					<canvas class="space-use-ratio" data-scale="<?php echo round($TSize/$ASize,3)?>"></canvas>
				</div>
				<ul class="detailed pull-right">
					<h2>详细情况: </h2>
					<li onclick="get('<?php echo ROOT_PATH."/home/netdisk/"?>')"><span><i class="iconfont">&#xe67c;</i>所有文件</span><span><?php echo $TSize?> MB</span></li>
					<li onclick="get('<?php echo ROOT_PATH."/home/recycle/"?>')"><span><i class="iconfont">&#xe669;</i>回收内容</span><span><?php echo $RSize?> MB</span></li>
					<li onclick="get('<?php echo ROOT_PATH."/home/share/"?>')"><span><i class="iconfont">&#xe602;</i>我的分享</span><span><?php echo $shareNum?></span></li>
					<li onclick="get('<?php echo ROOT_PATH."/home/account/"?>')"><span><i class="iconfont">&#xe623;</i>总共大小</span><span><?php echo $ASize?> MB</span></li>
				</ul>
			</div>
		</div>
	</div>
	<?php include( "template.php" ) ?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>
<script src="<?php echo ROOT_PATH;?>/static/js/home.js" id="alone-js"></script>