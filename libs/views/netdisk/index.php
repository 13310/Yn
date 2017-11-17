<div id="loading"></div>
<header class="header">
	<h1>本地硬盘</h1>
	<span id="new" class="new">新建</span>
	<div id="newPiece" class="newPiece">
		<ul>
			<li id="newPaper"><i class="iconfont">&#xe6ca;</i>新建文件夹</li>
			<li id="newFile"><i class="iconfont">&#xe632;</i>新建文本文档</li>
			<li id="uploadfile"><i class="iconfont">&#xe65c;</i>上传文件</li>
			<li id="uploadpaper"><i class="iconfont">&#xe619;</i>上传文件夹</li>
		</ul>
	</div>
	<div id="pathPiece" class="pathPiece">
		<h2 class="root">我的硬盘</h2>
		<ul id="path">
		</ul>
	</div>
	<div class="paste" id="paste">
		<i class="iconfont">&#xe6de;</i>
	</div>
	<div class="info" id="info" data-bubble="属性">
		i
	</div>
	<div class="eve" data-bubble="预览" id="eve">
		<i class="iconfont">&#xe689;</i>
	</div>
</header>
<section id="content" class="content">
	<div class="sidebar">
		<ul>
			<li class="area root" select>我的硬盘</li>
			<li class="area">回收站</li>
			<li class="area">我的设置</li>
			<li class="area"><a href="logout">登出账号</a></li>
		</ul>
	</div>
	<div class="list">
		<span id="nocontent">空空如也</span>
		<div class="paper" id="paper">
			<h1>文件夹</h1>
			<ul id="papers">
			</ul>
		</div>
		<div class="file" id="file">
			<h1>文件</h1>
			<ul id="files">
			</ul>
		</div>
	</div>
	<div class="property" id="property">
		<span id="propertyClose"></span>
		<h1 id="propertyName"></h1>
		<ul id="propertys">

		</ul>
		<div class="setting">
			<span id="rename" class="ste" data-bubble="重命名"><i class="iconfont">&#xe621;</i></span>
			<span id="copy" class="ste" data-bubble="复制"><i class="iconfont">&#xe626;</i></span>
			<span id="move" class="ste" data-bubble="移动"><i class="iconfont">&#xe66c;</i></span>
			<span id="download" class="ste" data-bubble="下载"><i class="iconfont">&#xe56d;</i></span>
			<span id="delete" class="ste" data-bubble="双击删除"><i class="iconfont">&#xe629;</i></span>
		</div>
	</div>
	<div class="preview" id="previewPlace">
		<div class="previewOper">
			<span id="previewExit"></span>
			<h2 id="previewTiele"></h2>
			<span id="previewDel"><i class="iconfont">&#xe629;</i></span>
			<span id="previewDown"><i class="iconfont">&#xe56d;</i></span>
		</div>
		<iframe id="preview" width="600" height="400" src="" name="preview"></iframe>
	</div>
</section>
<!--编辑快捷属性面板-->
<div id="shortPlace">
	<div class="formPlace">
		<div>
			<h2 id="shortName">我是谁</h2>
			<span class="shortClose" id="shortClose"></span>
		</div>
		<div class="shortFM">
			<label id="shortTips">我在这里干什么</label><br><br>
			<input type="text" id="shortInput" autofocus>
		</div>
		<div>
			<span id="shortEnter" class="confirm">确认</span>
			<span id="shortCancel" class="confirm">取消</span>
		</div>
	</div>
</div>
<div id="uploadPlace" class="uploadPlace">
	<div class="formPlace">
		<div>
			<h2 id="uploadName">上传文件</h2>
			<span class="shortClose" id="uploadClose"></span>
		</div>
		<form class="uploadFM" id="uploadFM" enctype="multipart/form-data" method="post">
			<input type="file" title="拖动文件到此或者点击上传" id="Upfile" name="file" class="Upfile">
			<div class="uploadBanner"><span id="filetips" class="filetips">未选择文件</span>文件上传</div>
		</form>
		<div>
			<span id="uploadEnter" class="uploadEnter confirm">确认</span>
			<span id="uploadCancel" class="confirm">取消</span>
		</div>
	</div>
</div>
<div id="uploadPlaces" class="uploadPlace">
	<div class="formPlace">
		<div>
			<h2 id="uploadNames">上传文件夹</h2>
			<span class="shortClose" id="uploadCloses"></span>
		</div>
		<form class="uploadFM" id="uploadFMs" enctype="multipart/form-data" method="post">
			<input type="file" title="拖动文件夹到此或者点击上传" id="Upfiles" name="file[]" class="Upfile" webkitdirectory>
			<div class="uploadBanner"><span id="filetipss" class="filetips">未选择任何内容</span>文件夹上传</div>
		</form>
		<div>
			<span id="uploadEnters" class="uploadEnter confirm">确认</span>
			<span id="uploadCancels" class="confirm">取消</span>
		</div>
	</div>
</div>
<div class="writePlace" id="writePlace">
	<div class="previewOper">
		<span id="writeExit"></span>
		<h2 id="writeTiele"></h2>
		<span id="writeEnter"></span>
	</div>
	<textarea id="write" name="write" autofocus placeholder="输入..."></textarea>
</div>