<div class="wrapper clearfix wrapper-netdisk">
	<?php
	include( "menu.php" );
	?>
	<div class="view">
		<?php include "viewHead.php"?>
		<div class="netdisk-menu clearfix">
			<ul class="file-tab pull-left">
				<li class="file-tab-menu new-f">新建</li>
				<div class="new-f-p">
					<ul>
						<li class="tab-new-file"><i class="iconfont">&#xe67c;</i>新建文件</li>
						<li class="tab-new-folder"><i class="iconfont">&#xe72d;</i>新建文件夹</li>
						<li class="tab-upload"><i class="iconfont">&#xe64b;</i>上传文件</li>
						<li class="tab-upload-dir"><i class="iconfont">&#xe619;</i>上传文件夹</li>
						<li class="tab-url-down" style="display: none"><i class="iconfont">&#xe6dd;</i>从URL下载文件</li>
					</ul>
				</div>
				<li class="file-tab-menu share-f">分享</li>
			</ul>
			<ul class="file-path pull-left">
				<li><i class="iconfont"></i></li>
			</ul>
		</div>
		<div class="view-main">
			<div class="view-thumbnail">
				<h1 class="view-files-name">文件夹</h1>
				<ul class="view-files"></ul>
				<h1 class="view-file-name">文件</h1>
				<ul class="view-file"></ul>
			</div>
		</div>
	</div>
	<?php include( "template.php" ) ?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>
<script src="<?php echo ROOT_PATH;?>/static/js/netdisk.js" id="alone-js"></script>