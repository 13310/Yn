<template id="popup">
	<div class="popup popup-window">
		<div class="popup-head">
			<span class="popup-title">窗口标题</span>
			<span class="popup-close" title="关闭"><i class="iconfont"></i></span>
			<span class="popup-zoom" title="缩放" data-window="1"><i class="iconfont"></i></span>
		</div>
		<div class="popup-main">
			<section class="popup-message">

			</section>
		</div>
	</div>
	<div class="popup-option">
		<button type="button" class="popup-cancel">取消</button>
		<button type="button" class="popup-enter">确认</button>
	</div>
	<input class="popup-input" type="text" name="popup-value" placeholder="">
	<form class="upload-panel" id="uploadFile" enctype="multipart/form-data" method="post">
		<input type="file" title="拖动文件到此或者点击上传" id="file" name="file[]" class="upload-file" multiple>
		<div class="uploadBanner">拖动到此或者点击</div>
	</form>
	<textarea class="popup-area" type="text" name="popup-textarea" autofocus placeholder="在此键入内容"></textarea>
	<form class="upload-panel" id="uploadFolder" enctype="multipart/form-data" method="post">
		<input type="file" title="拖动文件夹到此或者点击上传" id="folder" name="file[]" class="upload-folder" webkitdirectory>
		<div class="uploadBanner">拖动到此或者点击</div>
	</form>
	<div class="share-info">
		<div class="public-share">
			<input type="radio" class="share-bt" name="share" title="公开分享" checked value="public">
			<span>公开</span>
		</div>
		<div class="private-share">
			<input type="radio" class="share-bt" name="share" title="私密分享" value="private">
			<span>私密</span>
			<input type="text" class="share-password" name="share-password" placeholder="输入密钥" disabled maxlength="8">
		</div>
	</div>
</template>
<template id="right-menu">
	<ul class="right-menu">

	</ul>
	<ul id="default-menu">
		<li class="right-menu-option backing">返回(B)</li>
		<li class="right-menu-option going">前进(F)</li>
		<hr>
		<li class="right-menu-option break">刷新(E)</li>
		<li class="right-menu-option reload">重新加载页面(R)</li>
	</ul>
	<ul id="file-menu">
		<hr>
		<li class="right-menu-option open">打开(O)</li>
		<li class="right-menu-option download">下载(L)</li>
		<hr>
		<li class="right-menu-option cut">剪切(T)</li>
		<li class="right-menu-option copy">复制(C)</li>
		<li class="right-menu-option rename">重命名(M)</li>
		<li class="right-menu-option move-in-trash">删除(D)</li>
		<hr>
		<li class="right-menu-option attribute">属性(I)</li>
	</ul>
	<ul id="files-menu">
		<hr>
		<li class="right-menu-option open">打开(O)</li>
		<hr>
		<li class="right-menu-option cut">剪切(T)</li>
		<li class="right-menu-option copy">复制(C)</li>
		<li class="right-menu-option rename">重命名(M)</li>
		<li class="right-menu-option move-in-trash">删除(D)</li>
		<hr>
		<li class="right-menu-option attribute">属性(I)</li>
	</ul>
	<ul id="trash-menu">
		<li class="right-menu-option move-out-trash">还原</li>
		<li class="right-menu-option cut">剪切</li>
		<li class="right-menu-option del">删除</li>
		<li class="right-menu-option attribute">属性</li>
	</ul>
	<ul id="default-trash">
		<li class="right-menu-option backing">返回(B)</li>
		<li class="right-menu-option going">前进(F)</li>
		<hr>
		<li class="right-menu-option break">刷新(E)</li>
		<li class="right-menu-option reload">重新加载页面(R)</li>
		<hr>
		<li class="right-menu-option paste">粘贴(P)</li>
		<li class="right-menu-option del-all">清空回收站</li>
	</ul>
	<ul id="netdisk-append">
		<li class="right-menu-option new">
			新建(N)
			<ul class="menu-children">
				<li class="right-menu-option new-file">新建文件</li>
				<li class="right-menu-option new-folder">新建文件夹</li>
			</ul>
		</li>
		<li class="right-menu-option upload">
			上传(U)
			<ul class="menu-children">
				<li class="right-menu-option upload-file">上传文件</li>
				<li class="right-menu-option upload-folder">上传文件夹</li>
			</ul>
		</li>
		<li class="right-menu-option follow">
			排序(O)
			<ul class="menu-children">
				<li class="right-menu-option follow-name">按名称</li>
				<li class="right-menu-option follow-time">按时间</li>
				<li class="right-menu-option follow-size">按大小</li>
				<li class="right-menu-option follow-mime">按类型</li>
			</ul>
		</li>
		<hr>
		<li class="right-menu-option backing">返回(B)</li>
		<li class="right-menu-option going">前进(F)</li>
		<hr>
		<li class="right-menu-option break">刷新(E)</li>
		<li class="right-menu-option reload">重新加载页面(R)</li>
		<hr>
		<li class="right-menu-option paste">粘贴(P)</li>
	</ul>
</template>
<template id="player">
	<video class="player-video" controls="" autoplay="">
		<source src="" type="video/mp4">
	</video>
</template>