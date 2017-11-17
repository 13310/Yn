"use strict";
var fileData={};
var mimeArr={
    "msword":"word",
    "vnd.ms-office":"pub",
    "x-msaccess":"access",
    "vnd.openxmlformats-officedocument.spreadsheetml.sheet":"xlsx",
    "vnd.openxmlformats-officedocument.presentationml.presentation":"ppt",
    "zip":"rar",
    "x-7z-compressed":"rar",
    "x-rar":"rar",
    "x-gzip":"rar",
    "x-font-ttf":"font",
    "font-woff":"font",
    "pdf":"document",
    "epub+zip":"document"
};
//可打开文件列表
var canOpenFileList=["video","audio","text","image",'document','code'];
var present_file;
/*
 * 左键菜单
 */
function rightClick() {
    yn(".view-thumbnail").addEventListener('contextmenu',fileMouse,false);
    yn(".file-path").addEventListener('click',urlClick,false);
    yn(".view-thumbnail").addEventListener('click',fileClick,false);
    //绑定双击
    yn(".view-thumbnail").addEventListener("dblclick",function (event) {
        var ev = event||window.event;
        ev = ev.target.nodeName.toLowerCase()==="li"?ev.target:ev.target.parentNode;
        if(ev.className==='fileAmong file-selected'){
            if(!canOpenFileList.contains(ev.dataset.type)){
                return;
            }
        }
        fileDblClick(ev);
    },false)
}
rightClick();
/*
 * 右上角菜单
 */
function tab_new_file() {
    if(yn(".new-f-p-open")){
        yn(".new-f-p").className="new-f-p";
        return;
    }
    yn(".new-f-p").className="new-f-p new-f-p-open";
}
/*
 * 分享文件
 */
function tab_share_file() {
    if(present_file===""){return}
    var fileObject = present_file;
    function changeStatus() {
        if(this.value==="private"){
            yn(".share-password").removeAttribute('disabled');
        }else{
            yn(".share-password").setAttribute('disabled',"");
            yn(".share-password").value="";
        }
    }
    if(this.dataset.type==="unshare"){
        ajax({
            url:window.present+"/ajax.php",
            dataType:"json",
            type:"get",
            data:{
                "action":"unshare",
                "key":fileData[fileObject.dataset.id].share
            },
            success:function (result) {
                console.log(result);
                if(result.status==='success'){
                    closePopup();
                    output('取消成功','#28d959');
                    yn(".share-f").innerText="分享";
                    yn(".share-f").dataset.type="share";
                }else{
                    output('取消失败','#ff3718');
                }
            }
        })
    }
    else{
        popup({
            button:true,
            title:"分享",
            EnterValue:'分享',
            callback:function () {
                var share_passwd = yn(".share-password").value;
                if(share_passwd!==""&&share_passwd.length<4){
                    yn(".share-password").focus();
                    return;
                }
                var file = fileData[fileObject.dataset.id];
                ajax({
                    url:window.present+"/ajax.php",
                    dataType:"json",
                    type:"post",
                    data:{
                        "action":"share",
                        "u":file.path,
                        "n":file.name,
                        "m":file.mime,
                        "s":file.size,
                        'p':share_passwd
                    },
                    success:function (result) {
                        if(result.status==='success'){
                            closePopup();
                            output('分享成功','#28d959');
                            yn(".share-f").innerText="取消分享";
                            yn(".share-f").dataset.type="unshare";
                        }else{
                            output('分享失败','#ff3718');
                        }
                    }
                })
            }
        });
        var share_info = yn("#popup").content.querySelector(".share-info");
        yn(".popup-message").insertBefore(share_info.cloneNode(true),yn(".popup-option"));
        yns(".share-bt").forEach(function (t) {
            t.addEventListener('click',changeStatus,false);
        })
    }
}
function check_share() {
    if(fileData[present_file.dataset.id].share){
        yn(".share-f").style.display="inline-block";
        yn(".share-f").innerText="取消分享";
        yn(".share-f").dataset.type="unshare";
    }else{
        yn(".share-f").style.display="inline-block";
        yn(".share-f").innerText="分享";
        yn(".share-f").dataset.type="share";
    }
}
yn(".new-f").addEventListener("click",tab_new_file,false);
yn(".share-f").addEventListener("click",tab_share_file,false);
yn(".new-f-p").addEventListener("click",function (event) {
    var action = event.target.className.replace('tab-','');
    changeFile(action);
    yn(".new-f-p").className="new-f-p";
},false);
/*
 * 鼠标点击
 */
function fileMouse(event) {
    var ev = event||window.event;
    if(yn(".new-f-p-open")){
        yn(".new-f-p").className="new-f-p";
    }
    if(yn(".file-selected")){
        yn(".file-selected").className=yn(".file-selected").className.replace(" file-selected","");
        yn(".share-f").style.display="none";
    }
    var thisNode = ev.target.nodeName.toLowerCase()==="li"?ev.target:ev.target.parentNode;
    if(thisNode.nodeName.toLowerCase()==="li"){
        present_file = thisNode;
        // 选择
        thisNode.className+=" file-selected";
        // 启用分享按钮
        check_share();
        if(thisNode.parentNode.className==="view-files"){
            openMouseMenu(ev,"files");
        }else if(thisNode.parentNode.className==="view-file"){
            openMouseMenu(ev,"file");
            if(!canOpenFileList.contains(thisNode.dataset.type)){
                yn(".right-menu").querySelector(".open").className='right-menu-option open right-menu-disabled';
            }
            if(thisNode.dataset.type==='text'||thisNode.dataset.type==='code'){
                var edit = document.createElement("li");
                edit.className="right-menu-option edit";
                edit.innerText="编辑(E)";
                yn("#file-menu").insertBefore(edit,yn(".download"));
            }
        }
    }else{
        openMouseMenu(ev,"netdisk");
    }
    event.stopPropagation();
}
/*
 * 菜单目录点击
 */
function urlClick(event) {
    var ev = event||window.event;
    if(ev.target.dataset.present!==""){
        var url = ev.target.dataset.url||'/';
        getNetdiskData(decodeURIComponent(url))
    }
}
/*
 * 点击文件
 */
function fileClick(event) {
    var ev = event||window.event;
    if(yn(".file-selected")){
        yn(".file-selected").className=yn(".file-selected").className.replace(" file-selected","");
        yn(".share-f").style.display="none";
    }
    var thisNode = ev.target.nodeName.toLowerCase()==="li"?ev.target:ev.target.parentNode;
    if(thisNode.nodeName.toLowerCase()==="li") {
        present_file = thisNode;
        // 不启用文件夹分享,原因为不打算写压缩文件的功能
        if(present_file.className!=="paperAmong")check_share();

        thisNode.className+=" file-selected";
    }
}
/*
 * 鼠标双击
 */
function fileDblClick(event) {
    // 判断是直接调用还是传递标签调用,这是传递标签
    var thisNode = event||window.event;
    if(thisNode.nodeName.toLowerCase()==="li") {
        var data =fileData[thisNode.dataset.id];
        if(data.type==="dir"){
            getNetdiskData(data.path)
        }else{
            popup({
                title:data.name,
                full:true
            });
            loaderBegin(yn(".popup-message"));
            getFileData(data,player);
        }
    }
}
/*
 * 播放文件
 */
function player(data) {
    var url = window.URL.createObjectURL(data);
    var action = data.type.split("/")[0];
    if(action==="application"){
        action = data.type.split("/")[1];
    }
    var bindings={
        "image":function () {
            yn(".popup-message").innerHTML="<img src='"+url+"'/>";
        },
        "video":function () {
            var embed = document.createElement("embed");
            embed.src=url;
            embed.className="player-embed";
            embed.type = data.type;
            yn(".popup-message").appendChild(embed);
        },
        "text":function () {
            var reader = new FileReader();
            reader.readAsText(data);
            reader.addEventListener('load',function () {
                var text = document.createElement("div");
                text.className="text-read";
                text.innerText =this.result;
                yn(".popup-message").appendChild(text);
            },false);
        },
        "pdf":function () {
            var embed = document.createElement("embed");
            embed.src=url;
            embed.className="player-embed";
            embed.type = data.type;
            yn(".popup-message").appendChild(embed);
        },
        'audio':function () {
            var embed = document.createElement("embed");
            embed.src=url;
            embed.className="player-embed";
            embed.type = data.type;
            yn(".popup-message").appendChild(embed);
        }
    };
    if(action in bindings){
        bindings[action]();
    }
    loaderFinish();
}
/*
 * 排序列表
 */
function fileSort(key) {
    key = key||"name";
    // 临时存储文件
    let temp=[];
    // 读取上次规则
    let desc = null;
    if(localStorage.getItem("sort_desc")===null){
        localStorage.setItem("sort_desc","desc");
        desc = "desc";
    }else{
        desc = localStorage.getItem("sort_desc")==="desc"?"asc":"desc";
        localStorage.setItem("sort_desc",desc);
    }
    console.log(desc);
    // 记录排序规则
    localStorage.setItem("sort_fn",key);
    localStorage.setItem("sort_desc",desc);
    // 获取到文件
    yns(".view-file li").forEach((t)=>{
        let info =fileData[t.dataset.id];
        if(info.size.endsWith("MB")){
            info.size = info.size.replace("MB","")*1024+"KB";
        }
        let size = Number(info.size.replace("KB",""));
        temp.push({"name":t.innerText,"time":info.xtime,"size":size,"mime":info.mime,"id":t.dataset.id})
    });
    let str_sort = ()=>temp.sort((a,b)=>a[key].localeCompare(b[key],"zh"));
    let num_sort = ()=>temp.sort((a,b)=>{
        return (a[key] > b[key]) ? -1 : (a[key] < b[key]) ? 1 : 0;
    });
    if(key==="name"||key==="mime"){
        str_sort();
    }else {
        num_sort();
    }
    if(desc==="asc"){
        temp.reverse();
    }
    yn(".view-file").innerHTML="";
    temp.forEach(function (t) {
        let file_temp = document.createElement("li");
        file_temp.className="fileAmong";
        file_temp.dataset.id=t.id;
        let fileMime = t.mime.split("/")[0];
        if(fileMime==="application"){
            fileMime=mimeArr[t.mime.split("/")[1]];
        }
        if(fileMime==="text"){
            let index = t.name.lastIndexOf(".");
            let file_ext = t.name.substr(index+1).trim();
            fileMime = Controlgroup[file_ext]!==undefined?Controlgroup[file_ext]:fileMime;
        }
        file_temp.dataset.type=fileMime;
        file_temp.innerHTML='<div class="thumbnail-img"></div><span>'+t.name+'</span>';
        yn(".view-file").appendChild(file_temp);
    })
}
/*
 * 接收文件的内容
 */
function getFileData(data,callback) {
    ajax({
        url:window.present+"/ajax.php",
        dataType:"blob",
        data:{"action":"open","p":data.path},
        success:function (blob) {
            callback(blob)
        },
        fail:function () {
            output("网络错误","#ff3718");
            loaderFinish();
            closePopup();
        }
    })
}
/*
 * 文件操作集
 */
function changeFile(action) {
    let fileObject = present_file;
    let bindings={
        'cut':function () {
            waitPaste.action = 'cut';
            waitPaste.data = fileData[fileObject.dataset.id];
        },
        'copy':function () {
            waitPaste.action = 'copy';
            waitPaste.data = fileData[fileObject.dataset.id];
        },
        'edit':function () {
            getFileData(fileData[fileObject.dataset.id],readData);
            var name = fileData[fileObject.dataset.id]['name'];
            function readData(value) {
                var reader = new FileReader();
                reader.readAsText(value);
                reader.addEventListener('load',function () {
                    openEdit(this.result);
                },false);
            }
            function openEdit(value) {
                popup({
                    textarea:true,
                    textareaValue:value,
                    full:true,
                    button:true,
                    title:"编辑文件",
                    EnterValue:'更新',
                    callback:function (val) {
                        ajax({
                            url:window.present+"/ajax.php",
                            dataType:"json",
                            type:'post',
                            data:{"action":'new_file',"file_name":name,"file_value":val},
                            success:function (result) {
                                if(result.status === 'success'){
                                    output('更新成功','#28d959');
                                }else{
                                    output('更新失败','#ff3718');
                                }
                            }
                        })
                    }
                })
            }
        },
        'rename':function () {
            let url = fileData[fileObject.dataset.id].path;
            let pop = popup({
                title:"重命名",
                element:"输出新的名称:",
                input:true,
                button:true,
                inputValue:fileObject.innerText,
                close:true,
                callback:function (newName) {
                    if(newName!==fileObject.innerText.trim()){
                        submit('rename',url,newName,function (result) {
                            if(result.status==="success"){
                                closePopup();
                                getNetdiskData();
                                output('重命名成功','#28d959');
                            }else if(result.status==="warn"){
                                popup({
                                    title:"注意",
                                    element:"检测到以存在 "+newName+" 文件,以重命名为 "+result.data
                                });
                                getNetdiskData();
                            }
                        })
                    }else {
                        yn(".popup-input").focus();
                    }
                }
            });
        },
        'del':function () {
            var url = fileData[fileObject.dataset.id].path;
            submit('del',url,"",function (result) {
                if(result.status==="success"){
                    output('删除成功','#28d959');
                    getNetdiskData();
                }else {
                    output('删除失败','#ff3718');
                }
            })
        },
        'move-in-trash':function () {
            var url = fileData[fileObject.dataset.id].path;
            var name = fileData[fileObject.dataset.id].name;
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"Move_in_trash","p":url,"append":name},
                success:function (result) {
                    if(result.status==="success"){
                        output('移至回收站成功','#28d959');
                        fileObject.parentNode.removeChild(fileObject);
                        judge_file();
                    }else {
                        output('移至回收站失败','#ff3718');
                    }
                }
            })
        },
        'paste':function (affirm) {
            // 是否替换
            affirm = affirm||"";
            if(waitPaste.action==="t_cut"){
                ajax({
                    url:window.present+"/ajax.php",
                    dataType:"json",
                    data:{"action":"Move_out_trash","id":waitPaste.id,"assign":1},
                    success:function (result) {
                        if(result.status==="success"){
                            output("还原成功","#28d959");
                            getNetdiskData();
                        }else {
                            output("还原失败","#ff3718");
                        }
                        waitPaste={};
                    }
                });
                return;
            }
            submit(waitPaste.action,waitPaste.data.path,waitPaste.data.name,function (result) {
                if(result.status==="fail"&&result.code==="01"){
                    popup({
                        title:"文件剪切",
                        element:"检测到文件重复,是否替换文件?原文件会丢失",
                        button:true,
                        close:true,
                        callback:function (r) {
                            if(r)bindings['paste']('true');
                        }
                    })
                }else if(result.status==="success") {
                    if(waitPaste.action==="cut"){
                        output('移动成功','#28d959');
                    }else {
                        output('复制成功','#28d959');
                    }
                    closePopup();
                    getNetdiskData();
                }else if(result.status==="fail"&&result.code==="02"){
                    popup({
                        title:"文件复制",
                        element:"检测到文件夹重复,是否合并文件夹?这可能会丢失一些文件",
                        button:true,
                        close:true,
                        callback:function (r) {
                            if(r)bindings['paste']('true');
                        }
                    })
                }
            },affirm)
        },
        'upload':function () {
            popup({
                upload:true,
                uploadType:'file',
                title:"上传文件",
                button:true,
                EnterValue:'上传',
                callback:function (fileData) {
                    fileData.append('action','u_file');
                    ajax({
                        url:window.present+"/ajax.php",
                        dataType:"json",
                        type:"post",
                        progress:function (evt) {
                            var progress = document.createElement("div");
                            progress.className="upload-progress";
                            progress.innerHTML="<span>0%</span>";
                            yn(".popup-message").innerHTML="";
                            yn(".popup-message").appendChild(progress);
                            if (evt.lengthComputable) {
                                //evt.loaded：文件上传的大小   evt.total：文件总的大小
                                var percentCompvare = Math.round((evt.loaded) * 100 / evt.total);
                                yn(".upload-progress span").style.width = percentCompvare+"%";
                            }
                        },
                        data:fileData,
                        success:function (result) {
                            yn(".upload-progress").style.margin="9rem auto 1rem";
                            var span = document.createElement("span");
                            if (result.status==='success'){
                                span.innerText="上传成功";
                                yn(".popup-message").appendChild(span);
                            }else if(result.status==='warn') {
                                span.innerText=result.data+"个上传失败";
                                yn(".popup-message").appendChild(span);
                            }else {
                                span.innerText="上传失败";
                                yn(".popup-message").appendChild(span);
                            }
                            getNetdiskData();
                        }
                    });
                }
            });
        },
        'upload-dir':function () {
            popup({
                upload:true,
                uploadType:'folder',
                button:true,
                title:"上传文件夹",
                EnterValue:'上传',
                callback:function (fileData) {
                    fileData.append('action','u_folder');
                    ajax({
                        url:window.present+"/ajax.php",
                        dataType:"json",
                        type:"post",
                        progress:function (evt) {
                            var progress = document.createElement("div");
                            progress.className="upload-progress";
                            progress.innerHTML="<span>0%</span>";
                            yn(".popup-message").innerHTML="";
                            yn(".popup-message").appendChild(progress);
                            if (evt.lengthComputable) {
                                //evt.loaded：文件上传的大小   evt.total：文件总的大小
                                var percentCompvare = Math.round((evt.loaded) * 100 / evt.total);
                                yn(".upload-progress span").style.width = percentCompvare+"%";
                            }
                        },
                        data:fileData,
                        success:function (result) {
                            yn(".upload-progress").style.margin="9rem auto 1rem";
                            var span = document.createElement("span");
                            if (result.status==='success'){
                                span.innerText="上传成功";
                                yn(".popup-message").appendChild(span);
                            }else if(result.status==='warn'&&result.code==='364') {
                                span.innerText=result.data+"个上传失败";
                                yn(".popup-message").appendChild(span);
                            }else if(result.status==='warn'&&result.code==="01"){
                                span.innerText="上传成功";
                                yn(".popup-message").appendChild(span);
                                span = document.createElement("div");
                                span.innerText="检测到文件夹重复,自动重命名为:"+result.data;
                                yn(".popup-message").appendChild(span);
                            }
                            else {
                                span.innerText="上传失败";
                                yn(".popup-message").appendChild(span);
                            }
                            getNetdiskData();
                        }
                    });
                }
            });
        },
        'new-folder':function () {
            popup({
                input:true,
                inputName:'folder_name',
                button:true,
                element:'创建一个新的文件夹',
                title:"创建文件夹",
                EnterValue:'创建',
                callback:function (value) {
                    if(value === ""){
                        yn(".popup-input").focus();
                        return;
                    }
                    var status = true;
                    if(yn(".paperAmong")){
                        yns(".paperAmong").forEach(function (t) {
                            if(t.innerText===value){
                                output('文件夹存在',"#ff3718");
                                status = false;
                            }
                        });
                    }
                    var str = ['\\','/',':','.','*','?','"<','>','|'];
                    str.forEach(function (t) {
                        if(value.indexOf(t)>=0){
                            output('不能存在\\/:*?".<>|',"#ff3718");
                            status = false;
                        }
                    });
                    if(!status){return}
                    submit('new_folder',"",value,function (result) {
                        if(result.status==="success"){
                            output('创建成功','#28d959');
                            closePopup();
                            getNetdiskData();
                        }else {
                            output('创建失败','#ff3718');
                        }
                    })
                }
            })
        },
        'new-file':function () {
            popup({
                input:true,
                inputName:'file_name',
                button:true,
                element:'输入文件名',
                title:"创建文件",
                EnterValue:'创建',
                callback:function (value) {
                    if(value === ""){
                        yn(".popup-input").focus();
                        return;
                    }
                    var status = true;
                    if(yn(".view-thumbnail li")){
                        yns(".view-thumbnail li").forEach(function (t) {
                            if(t.innerText.trim()===value){
                                output('已存在',"#ff3718");
                                status = false;
                            }
                        });
                    }
                    var str = ['\\','/',':','*','?','"<','>','|'];
                    str.forEach(function (t) {
                        if(value.indexOf(t)>=0){
                            output('不能存在\\/:*?"<>|',"#ff3718");
                            status = false;
                        }
                    });
                    if(!status){return}
                    popup({
                        textarea:true,
                        button:true,
                        title:"创建文件",
                        EnterValue:'创建',
                        callback:function (val) {
                            ajax({
                                url:window.present+"/ajax.php",
                                dataType:"json",
                                type:'post',
                                data:{"action":'new_file',"file_name":value,"file_value":val},
                                success:function (result) {
                                    if(result.status === 'success'){
                                        closePopup();
                                        output('创建成功','#28d959');
                                        getNetdiskData();
                                    }else{
                                        output('创建失败','#ff3718');
                                    }
                                }
                            })
                        }
                    })
                    }
                })
            },
        'url-down':function () {
            popup({
                title:"下载",
                element:"输入URL地址:",
                input:true,
                button:true,
                close:true,
                callback:function (url) {
                    var reg = /(https?|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]/;
                    if(!reg.test(url)){
                        output('地址不正确','#ff3718');
                        return;
                    }
                    loaderBegin(yn(".popup"));
                    window.onbeforeunload = function(event){
                        return true;
                    };
                    ajax({
                        url:window.present+"/ajax.php",
                        dataType:"json",
                        data:{"action":"url_down","url":url},
                        success:function (result) {
                            if(result.status==="success"){
                                getNetdiskData();
                                output("下载成功","#57db74");
                            }else{
                                output("下载成功","#ff3718");
                            }
                            closePopup();
                            loaderFinish();
                            window.onbeforeunload = null;
                        }
                    })
                }
            });
        },
        'attribute':function () {
            var data = fileData[present_file.dataset.id];
            var pop = popup({
                title:"属性"
            });
            yn("#"+pop.id).className+=" file-attributes-w";
            var ul = document.createElement("ul");
            ul.setAttribute("class","file-attributes");
            var li = document.createElement("li");
            li.innerHTML=`<span>名称</span><input value='${data.name}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>类型</span><input value='${data.type}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>大小</span><input value='${data.size}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>位置</span><input value='${decodeURIComponent(data.path)}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>所有者</span><input value='${data.user}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>修改时间</span><input value='${data.xtime}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>创建时间</span><input value='${data.ctime}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>共享</span><input value='${data.share?"共享中":"没有"}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            yn(".popup-message").appendChild(ul);
        },
        'download':function () {
            let data = fileData[present_file.dataset.id];
            let pop = popup({
                title:"下载",
                element:"即将开始下载: "+data.name
            });
            getFileData(data,function (blob) {
                var a = document.createElement("a");
                a.innerHTML = "没下载,重新下载"+data.name;
                a.download = data.name;
                a.style.display="block";
                a.href=URL.createObjectURL(blob);
                yn(".popup-message").appendChild(a);
                // 模拟点击事件
                var evt = document.createEvent("MouseEvents");
                evt.initEvent("click",false,false);
                a.dispatchEvent(evt);
            });
        },
        'follow':function () {
            fileSort('name')
        },
        'follow-name':function () {
            this.follow();
        },
        'follow-time':function () {
            fileSort('time')
        },
        'follow-size':function () {
            fileSort('size')
        },
        'follow-mime':function () {
            fileSort('mime')
        }
    };
    if(action in bindings){
        bindings[action]();
        present_file=null;
        if(yn(".share-f")){yn(".share-f").style.display="none"}
    }else{
        throw "Can not find >"+action+"< operating";
    }

    function submit(action,path,append,callback,affirm) {
        ajax({
            url:window.present+"/ajax.php",
            dataType:"json",
            data:{"action":action,"p":path,"append":append,"affirm":affirm},
            success:function (result) {
                callback(result);
            }
        })
    }
}
/*
 * 获取内容
 */
function getNetdiskData(path,cache) {
    loaderBegin(yn(".view"));
    path = path||getCookie("last_path")||"/";
    cache = cache||false;
    if(cache){
        throw "Warning:cache has been Disabled";
    }
    ajax({
        url:window.present+"/ajax.php",
        dataType:"json",
        data:{"action":"file","p":path},
        success:function (data) {
            if(data.status==="error"){
                if(path!=="/"){
                    getNetdiskData("/");
                }else{
                    output(data.code,"#ff3718");
                }
                return;
            }
            yn(".view-file").innerHTML="";
            yn(".view-files").innerHTML="";
            yn(".file-path").innerHTML='<li><i class="iconfont"></i></li>';
            data.forEach(function (value) {
                if(value.type==="file"){
                    yn(".view-file-name").innerText="文件";
                    fileData[value.id]={
                        name:value.name,
                        path:value.path,
                        mime:value.fileMime,
                        type:value.fileMime,
                        size:value.size,
                        share:value.share,
                        user:"我",
                        xtime:value.xtime,
                        ctime:value.ctime
                    };
                    var temp = document.createElement("li");
                    temp.className="fileAmong";
                    temp.dataset.id=value.id;
                    var fileMime = value.fileMime.split("/")[0];
                    if(fileMime==="application"){
                        fileMime=mimeArr[value.fileMime.split("/")[1]];
                    }
                    if(fileMime==="text"){
                        let index = value.name.lastIndexOf(".");
                        let file_ext = value.name.substr(index+1);
                        fileMime = Controlgroup[file_ext]!==undefined?Controlgroup[file_ext]:fileMime;
                    }
                    temp.dataset.type=fileMime;
                    temp.innerHTML='<div class="thumbnail-img"></div><span>'+value.name+'</span>';
                    yn(".view-file").appendChild(temp);
                }else if(value.type==="folder"){
                    yn(".view-files-name").innerText="文件夹";
                    fileData[value.id]={
                        name:value.name,
                        path:value.path,
                        type:"dir",
                        size:"—",
                        share:value.share,
                        user:"我",
                        xtime:value.xtime,
                        ctime:value.ctime
                    };
                    var folderTemp = document.createElement("li");
                    folderTemp.className="paperAmong";
                    folderTemp.dataset.id=value.id;
                    folderTemp.innerText=value.name;
                    yn(".view-files").appendChild(folderTemp);
                }else{
                    var urlPath="";
                    for (var key in value.url){
                        // for...in会遍历所有可枚举的属性,使用hasOwnProperty来判断是否是属于自己的属性.在这项目定义的属性已被设置不可遍历,但是严格起见
                        if(value.url.hasOwnProperty(key)){
                            if(value.url[key]!==""){
                                urlPath+="/"+value.url[key];
                                var pathTemp = document.createElement("li");
                                pathTemp.innerText=value.url[key];
                                pathTemp.dataset.url=encodeURIComponent(urlPath);
                                if(value.url.length-1===Number(key)){
                                    pathTemp.dataset.present="";
                                }
                                yn(".file-path").appendChild(pathTemp)
                            }
                        }
                    }
                }
            });
            //储存数据
            localStorage.setItem(path,JSON.stringify(fileData));
            loaderFinish();
            judge_file();
            //移除
            present_file="";
            if(yn('.share-f').style.display==="inline-block"){
                yn('.share-f').style.display="none"
            }
        },
        fail:function () {
            output("网络错误","#ff3718");
            loaderFinish();
        }
    })
}
getNetdiskData();
/*
 * 判定是否存在文件或文件夹
 */
function judge_file() {
    //没有文件夹
    if(!yn(".view-files li")){
        yn(".view-files-name").innerText="";
    }
    if(!yn(".view-file li")){
        yn(".view-file-name").innerText="";
    }
    if(!yn(".view-files li")&&!yn(".view-file li")){
        var temp = document.createElement("div");
        temp.className="no-c";
        temp.innerHTML="<img src='"+window.present+"/static/img/nofile.png' alt=''><p>快来上传些文件吧</p>"
        yn(".view-thumbnail").appendChild(temp);
    }else{
        if(yn(".no-c")){
            yn(".view-thumbnail").removeChild(yn(".no-c"));
        }
    }
}