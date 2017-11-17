<div class="wrapper clearfix wrapper-share">
	<?php include( "menu.php" ) ?>
	<div class="view">
		<?php include "viewHead.php"?>
		<div class="view-main">
			<table class="share-list" cellspacing="0">
				<thead>
					<tr class="share-tr share-tr-head">
						<th style="width: 200px;">名称</th>
						<th>大小</th>
						<th>时间</th>
						<th>浏览</th>
						<th>保存</th>
						<th>下载</th>
						<th>链接</th>
						<th>密码</th>
						<th>删除</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($all_share as $value):?>
					<tr class="share-tr share-tr-body" data-id="<?php echo $value['key']?>">
						<td class="share-file-name"><i class="iconfont"><?php echo $value['dir']?"&#xe60e;":"&#xe67c;"?></i><?php echo $value['name']?></td>
						<td class="share-file-size"><?php echo $value['size']?></td>
						<td class="share-file-time"><?php echo $value['time']?></td>
						<td class="share-file-scan"><?php echo $value['num_of_look']?>次</td>
						<td class="share-file-saves"><?php echo $value['num_of_save']?>次</td>
						<td class="share-file-downs"><?php echo $value['num_of_download']?>次</td>
						<td class="share-file-url"><a href="<?php echo $value['url']?>" target="_blank"><?php echo $value['url']?></a></td>
						<td class="share-file-password"><input type="text" name="share-password" title="私密分享的密钥" value="<?php echo $value['password']?>" maxlength="8"><i class="iconfont share-file-password-enter" title="确认"></i></td>
						<td class="share-file-remove"><i class="iconfont" style="font-size: 22px">&#xe6f7;</i></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
		<div class="s-setting-save clearfix hide">
			<div class="s-setting-animation pull-left">
				<i class="iconfont"></i>
			</div>
			<div class="s-setting-tips pull-left">
				正在保存设置中
			</div>
		</div>
	</div>
	<?php include( "template.php" ) ?>
</div>
<?php foot()?>
<script src="<?php echo ROOT_PATH;?>/static/js/index.js"></script>
<script src="<?php echo ROOT_PATH;?>/static/js/share.js" id="alone-js"></script>