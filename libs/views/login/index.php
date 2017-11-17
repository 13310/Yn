<div class="wrapper">
	<img class="bg" src="<?php echo $login_pic?>">
	<div class="slogan">
		<p class="slogan-zh"><?php echo $login_zh?></p>
		<p class="slogan-en"><?php echo $login_en?></p>
	</div>
	<main class="main" style="transform: rotateY(<?php echo $deg?>deg);">
		<form class="loginFrom" action="login.php?a=login" method="post">
			<h1>登录</h1>
			<input class="input" name="uname" type="text" placeholder="用户名" value="<?php getCookie('UName')?>" required>
			<input class="input" name="upass" type="password" placeholder="密码" required>
			<div class="validate">
				<input name="uvalidate" type="text" placeholder="验证码" required maxlength="4">
				<img src="<?php getUrl()?>/CAPTCHA" onclick="this.src='<?php getUrl()?>/CAPTCHA?'+Math.random()" alt="验证码">
			</div>
			<input name="checkMEM" type="checkbox" <?php checkCheckBox('UName')?>>记住用户名
			<input name="checkAvoid" type="checkbox">10天免登录
			<button type="submit" class="submit">登录</button>
			<a href="javascript:move('register')">注册</a>
			<a href="javascript:move('forget')">找回密码</a>
			<div class="activation">
				<h1>尚未激活</h1>
				<div id="CAPEMAIL-tips">你的邮箱地址:</div>
				<input type="text" id="CAPEMAIL" name="CAPEMAIL" placeholder="激活邮箱地址" value="">
				<div id="CAPEMAIL-warn">此邮箱已被使用,不能用于激活账户</div>
				<button type="button" class="submit">激活</button>
				<a>登录</a>
			</div>
		</form>
		<form class="registerFrom" action="login.php?a=register" method="post">
			<h1>注册</h1>
			<input class="input" name="rname" type="text" placeholder="用户名" minlength="4" maxlength="16" required>
			<input class="input" name="rpass" type="text" placeholder="密码" minlength="6" maxlength="18" required>

			<input class="input" name="remail" type="email" placeholder="邮箱地址" required>
			<div class="validate">
				<input name="rvalidate" type="text" placeholder="验证码" required maxlength="4">
				<img src="<?php getUrl()?>/CAPTCHA" onclick="this.src=window.present+'/CAPTCHA/?'+Math.random()" alt="验证码">
			</div>
			<button type="submit" class="submit">注册</button>
			<a href="javascript:move('login')">去登录</a>
			<div class="registerTips">
				<div>
					<h1>:)</h1>
					<p>账号注册成功,请前往邮箱进行激活</p>
					<p style="font-size: 1.4rem;">如果未收到邮件,请先登录再尝试激活</p>
				</div>
				<a>登录</a>
			</div>
		</form>
		<form class="forgetFrom" action="login.php?a=forget" method="post">
			<h1>忘记密码</h1>
			<input class="input" name="fname" type="text" placeholder="用户名" required>

			<input class="input" name="femail" type="email" placeholder="邮箱地址" required>
			<div class="validate">
				<input name="fvalidate" type="text" placeholder="验证码" required>
				<img src="<?php getUrl()?>/CAPTCHA" onclick="this.src='<?php getUrl()?>/CAPTCHA?'+Math.random()" alt="验证码">
			</div>
			<button type="submit" class="submit">忘记</button>
			<a href="javascript:move('login')">去登录</a>
		</form>
	</main>
	<?php foot()?>
	<script src="<?php getUrl()?>/static/js/login.js"></script>
</div>