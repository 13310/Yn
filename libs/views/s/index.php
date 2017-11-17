<div class="wrapper">
	<div class="header">
		<a class="trademark"href="<?php echo ROOT_PATH?>">
			<div class="trademark-logo pull-left">
				<img src="<?php echo ROOT_PATH?>/static/img/logo.png" alt="">
			</div>
			<div class="trademark-title pull-left"><h1>青苹果云盘</h1></div>
		</a>
	</div>
	<div class="main">
		<?php if($status):?>
			<div class="info">
				<?php if($file_password):?>
				<div class="password-verification">
					<h3>用户<?php echo $file_user;?>分享了私密文件</h3>
					<div class="password">
						<p>请输入密码来进行访问</p>
						<form action="">
							<input type="text" name="p" title="文件验证密码">
							<p><?php echo $pass_tips?></p>
							<button type="submit">提交</button>
						</form>
					</div>
				</div>
				<?php else:?>
				<div class="share-file-place">
					<div class="menu">
						<p><?php echo $file_name;?></p>
						<a href="<?php echo $url?>">下载(<?php echo $file_size;?>)</a>
					</div>
				</div>
				<?php endif;?>
			</div>
		<?php else:?>
			<div class="failure">
				<h1>:(</h1>
				<p>啊哦，你所访问的页面不存在了。</p>
				<p>可能的原因：</p>
				<p>1.在地址栏中输入了错误的地址。</p>
				<p>2.你点击的某个链接已过期。</p>
				<p><a href="javascript:history.go(-1)">返回上一级</a><a href="<?php echo ROOT_PATH?>">返回首页</a></p>
			</div>
		<?php endif;?>
	</div>
	<div class="footer">
		&copy;2017 YnetDisk版权所有
	</div>
</div>