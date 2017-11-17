<div class="view view-info">
	<?php include "viewHead.php"?>
	<div class="view-menu">
		<ul>
			<li class="selected">概述</li>
		</ul>
	</div>
	<div class="view-main">
		<div class="narrative">
			<div class="personal-info">
				个人信息
				<ul>
					<li>
						<div class="text-left">昵称:</div><div class="text-right"><input type="text" name="username" value="<?php echo $UserName;?>"><i class="iconfont personal-info-enter" title="保存"></i></div>
					</li>
					<li>
						<div class="text-left">邮箱地址:</div><div class="text-right"><input type="text" name="useremail" value="<?php echo $UserEmail;?>"><i class="iconfont personal-info-enter" title="确认"></i></div>
					</li>
					<li>
						<div class="text-left">密码:</div><div class="text-right"><input type="password" name="userpasswd" value="******"><i class="iconfont personal-info-enter" title="确认"></i></div>
					</li>
				</ul>
			</div>
			<div class="has-space">
				已用空间
				<p><?php echo $TSize;?> MB/<?php echo $ASize;?> MB</p>
				<ul>
					<li><div class="text-left"><i class="iconfont" style="font-size: 1.2rem">&#xe623;</i>云盘</div><div class="text-right"><?php echo $TSize;?> MB</div></li>
					<li><div class="text-left"><i class="iconfont">&#xe669;</i>回收站</div><div class="text-right"><?php echo $RSize;?> MB</div></li>
					<li><div class="text-left"><i class="iconfont" style="font-size: 1.8rem">&#xe71e;</i>可用空间</div><div class="text-right"><?php echo $ASize-$TSize;?> MB</div></li>
				</ul>
				<div class="progress"><span style="width: 5%"></span></div>
			</div>
			<div class="account-type">
				账户类型
				<ul>
					<li><div class="text-left">用户等级</div><div class="text-right">普通</div></li>
					<li><div class="text-left">储存空间</div><div class="text-right"><?php echo $ASize/1024;?> GB</div></li>
					<li><button type="button" class="button-default" onclick="updateThisAccount()">升级空间</button></li>
				</ul>
			</div>
			<div class="remove-account">
				删除账户信息
				<p><b style="color: red">警告!</b>此操作会删除你的所有信息,你的所有功能将无法使用,你亦无法再次使用此账户登录</p>
				<button type="button" class="button-gray" onclick="removeThisAccount()">删除</button>
			</div>
		</div>
	</div>
</div>