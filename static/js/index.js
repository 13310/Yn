// ajax加载内容
// 如果检测到属性不加入ajax套餐
yns(".menu li").forEach(function (t) {
    if(t.dataset.ajax==='none'){return}
    t.addEventListener('click',ajaxLoadPage,false)
});
// 是否有待复制列表
var waitPaste={};
let Controlgroup={
    // 图片
    "jpg":"image",
    "png":"image",
    "jpge":"image",
    "bmp":"image",
    "webp":"image",
    // 文档
    "pdf":"document",
    "epub":"document",
    // 音乐
    "mp3":"audio",
    "wav":"audio",
    "flac":"audio",
    "ogg":"audio",
    // 视频
    "mp4":"video",
    "avi":"video",
    "rmvb":"video",
    "rm":"video",
    "mkv":"video",
    "asf":"video",
    "mov":"video",
    "mpeg":"video",
    // 压缩包
    "zip":"rar",
    "rar":"rar",
    "7z":"rar",
    "tgr":"rar",
    "iso":"rar",
    "bz2":"rar",
    //代码
    "html":"code",
    "js":"code",
    "php":"code",
    "java":"code",
    "c":"code",
    "h":"code",
    "py":"code"

};
function ajaxLoadPage() {
    //如果点击的li已经选中则退出
    if(this.className.indexOf('selected')>=0){return}
    // 获取class name
    var oldClassName = this.className;
    // 拼写url地址
    var url = oldClassName!=="home"?window.present+"/home/"+oldClassName:window.present+"/home/";
    get(url)
}
function get(url) {
    loaderBegin(yn(".view"));
    if(typeof url!=="string")return;
    ajax({
        url:url,
        success: function(response) {
            var state = {
                title:document.title,
                url:url,
                data:response
            };
            // 写入历史记录
            writeHistory(state);
            // 更改页面内容
            pageUpdate(response);
        }
    })
}
function pageUpdate(content) {
    if(typeof content!=="string")return;
    var temp = document.createElement("div");
    temp.innerHTML = content;
    // 页面专用样式名
    var classname = temp.yn(".wrapper").className;
    // 标题
    var title = temp.yn("title").text;
    // 容器
    var container = temp.yn(".container");
    // 视图
    var view = temp.yn(".view");
    // 脚本
    var script = temp.yn("#alone-js").src;
    // 更新标题
    yn("title").innerText=title;
    // 更新页面专用样式名
    if(yn(".wrapper").className!==classname){
        yn(".wrapper").className=classname;
    }
    // 更新容器
    if(yn(".container")){yn(".wrapper").removeChild(yn(".container"))}
    if(container)yn(".wrapper").appendChild(container);
    // 更新视图
    yn(".wrapper").removeChild(yn(".view"));
    if(view)yn(".wrapper").appendChild(view);
    // 更新脚本
    yn("body").removeChild(yn("#alone-js"));
    var tempScript = document.createElement("script");
    tempScript.src = script;
    tempScript.id = "alone-js";
    yn("body").appendChild(tempScript);
    // 移除已选择样式
    yn(".selected").className=yn(".selected").className.replace(" selected","");
    // 重新执行分配函数
    autoAttribution();
    yn(".view-more").addEventListener('click',viewMore,false);
    loaderFinish();
}
// 前进后退功能
window.onpopstate = function (evt) {
    var state = evt.state;
    if(state!==null){
        pageUpdate(state.data);
    }
};
// 从url自动读取选择项目
function autoAttribution() {
    var urlarr = location.pathname.split("/");
    var menuBelong = urlarr[3];
    var containBelong = urlarr[4];
    if(menuBelong===undefined||menuBelong===""||menuBelong==="index"){
        yn(".home").className="home selected";
    }
    yns(".menu li").forEach(function (t) {
        if(t.className===menuBelong) {
            t.className=menuBelong+" selected"
        }
    });
    if(yn(".container")){
        if(containBelong===undefined||containBelong===""){
            yn(".info").className="info selected";
        }else {
            yns(".container-option>li").forEach(function (t) {
                if(t.className===containBelong){
                    t.className=containBelong+" selected";
                }
            })
        }
    }
}
autoAttribution();
function logout() {
    popup({
        title:"退出登录",
        element:"你确定退出登录?",
        button:true,
        EnterValue:"退出",
        callback:function (status) {
            if(status){
                window.location = window.present+"/logout.php";
            }
        }
    });
}
yn(".logout").addEventListener('click',logout,false);
function viewMore() {
    if(yn(".view-more-open")){
        this.className="view-more clearfix";
        yn(".view-morePanel").className="view-morePanel view-morePanel-out";
        return;
    }
    this.className+=" view-more-open";
    yn(".view-morePanel").className="view-morePanel view-morePanel-in";
}
yn(".view-more").addEventListener('click',viewMore,false);
function openMouseMenu(event,cum) {
    // 加载那些模板到菜单
    cum = cum||null;
    if(yn(".right-menu")){
        yn(".view").removeChild(yn(".right-menu"));
    }
    //阻止默认事件
    event.preventDefault();
    var cums = {
      'files':function () {
          var fileTemp = temps.querySelector("#files-menu");
          yn(".right-menu").appendChild(fileTemp.cloneNode(true));
      },
        'file':function () {
            var filesTemp = temps.querySelector("#file-menu");
            yn(".right-menu").appendChild(filesTemp.cloneNode(true));
        },
        'trash':function () {
            var trashTemp = temps.querySelector("#trash-menu");
            yn(".right-menu").appendChild(trashTemp.cloneNode(true));
        },
        'netdisk':function () {
            var nTemp = temps.querySelector("#netdisk-append");
            yn(".right-menu").appendChild(nTemp.cloneNode(true));
            if(waitPaste.action===undefined){
                yn(".right-menu").querySelector(".paste").className='right-menu-option paste right-menu-disabled';
            }
        },
        'default-trash':function () {
            var nTemp = temps.querySelector("#default-trash");
            yn(".right-menu").appendChild(nTemp.cloneNode(true));
            if(waitPaste.action!=="cut"){
                yn(".right-menu").querySelector(".paste").className='right-menu-option paste right-menu-disabled';
            }
            if(!yn(".view-thumbnail li")){
                yn(".right-menu").querySelector(".del-all").className='right-menu-option del-all right-menu-disabled';
            }
        }
    };
    var x = event.clientX;
    var y = event.clientY;
    var temps = yn("#right-menu").content;
    var temp = temps.querySelector(".right-menu");
    yn(".view").appendChild(temp.cloneNode(true));
    var menuDiv = yn(".right-menu");
    if(cum in cums){
        cums[cum]();
    }else {
        var defaultTemp = temps.querySelector("#default-menu");
        yn(".right-menu").appendChild(defaultTemp.cloneNode(true));
        //判断前进是否可以
        if(history.state===null||history.state.id===history.length-1){
            menuDiv.querySelector(".going").className='right-menu-option going right-menu-disabled';
        }
    }
    var pageWidth = document.body.clientWidth;
    var pageHeight = document.body.clientHeight;
    var elWidth = yn(".right-menu").clientWidth;
    var elHeight = yn(".right-menu").clientHeight;
    var xaxis=x+elWidth<pageWidth?x+"px":(x-elWidth)+"px";
    var yaxis=y+elHeight<pageHeight?y+"px":(y-elHeight)+"px";
    var yright = x+elWidth+50<pageWidth?"-103px":"267px";
    menuDiv.style.left=xaxis;
    menuDiv.style.top=yaxis;
    if(yn(".menu-children")){
        yns(".menu-children").forEach(function (t) {
            t.style.right = yright;
        })
    }
    yn(".right-menu").addEventListener("mousedown", function (eb) {
        mouseMenu(eb);
        eb.stopPropagation();
    },false);
}
document.addEventListener("mousedown",function (ev) {
    if(ev!==2){closeMouseMenu()}
},false);
function closeMouseMenu() {
    if(yn(".right-menu")){
        yn(".view").removeChild(yn(".right-menu"));
    }
}
function mouseMenu(ev) {
    if(ev.target.className.indexOf('right-menu-disabled')>1)return;
    var action = ev.target.className.replace("right-menu-option ","");
    var bindings ={
        'backing':function () {
            history.go(-1);
        },
        'going':function () {
            history.go(1);
        },
        'break':function () {
            get(location.href);
        },
        'reload':function () {
            history.go(0);
        },
        'open':function () {
            fileDblClick(present_file);
            closeMouseMenu();
        }
    };
    if(action in bindings){
        bindings[action]();
    }else {
        changeFile(action);
        closeMouseMenu();
    }
}