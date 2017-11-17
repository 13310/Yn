"use strict";
var present_file = null;
var fileData={};
function rightClick() {
    yn(".view-thumbnail").addEventListener('contextmenu',fileMouse,false);
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
 * 鼠标点击
 */
function fileMouse(event) {
    var ev = event||window.event;
    if(yn(".new-f-p-open")){
        yn(".new-f-p").className="new-f-p";
    }
    if(ev.button!==2)return;
    if(yn(".file-selected")){
        yn(".file-selected").className=yn(".file-selected").className.replace(" file-selected","");
    }
    var thisNode = ev.target.nodeName.toLowerCase()==="li"?ev.target:ev.target.parentNode;
    if(thisNode.nodeName.toLowerCase()==="li"){
        present_file = thisNode;
        // 选择
        thisNode.className+=" file-selected";
        openMouseMenu(ev,"trash");
    }else{
        openMouseMenu(ev,"default-trash");
    }
    event.stopPropagation();
}
/*
 * 点击文件
 */
function fileClick(event) {
    var ev = event||window.event;
    if(yn(".file-selected")){
        yn(".file-selected").className=yn(".file-selected").className.replace(" file-selected","");
    }
    var thisNode = ev.target.nodeName.toLowerCase()==="li"?ev.target:ev.target.parentNode;
    if(thisNode.nodeName.toLowerCase()==="li") {
        present_file = thisNode;
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
        let data =fileData[thisNode.dataset.id];
        let pop = popup({
            title:"属性"
        });
        yn("#"+pop.id).className+=" file-attributes-w";
        let ul = document.createElement("ul");
        ul.setAttribute("class","file-attributes");
        let li = document.createElement("li");
        li.innerHTML=`<span>名称</span><input value='${data.name}' readonly/>`;
        ul.appendChild(li.cloneNode(true));
        if(data.type==="dir"){
            li.innerHTML=`<span>类型</span><input value='文件夹' readonly/>`;
        }else{
            let index = data.name.lastIndexOf(".");
            let file_ext = data.name.substr(index+1);
            li.innerHTML=`<span>类型</span><input value='${file_ext} 文件' readonly/>`;
        }
        ul.appendChild(li.cloneNode(true));
        data.path = decodeURIComponent(data.path);
        let time = new Date(data.time);
        if(data.path==="/"){
            li.innerHTML=`<span>原位置</span><input value='根目录' readonly/>`;
        }else{
            li.innerHTML=`<span>原位置</span><input value='${data.path}' readonly/>`;
        }
        ul.appendChild(li.cloneNode(true));
        li.innerHTML=`<span>大小</span><input value='${data.size} MB' readonly/>`;
        ul.appendChild(li.cloneNode(true));
        if(data.type==="dir"){
            li.innerHTML=`<span>包含</span><input type="text" value="${data.dir_count} 文件夹 ${data.file_count} 文件" readonly/>`;
            ul.appendChild(li.cloneNode(true));
        }
        li.innerHTML=`<span>删除时间</span><input type="text" value="${time.getFullYear()}年${time.getMonth()}月${time.getDay()}日 ${time.getHours()}:${time.getMinutes()}" readonly/>`;
        ul.appendChild(li.cloneNode(true));
        yn("#"+pop.id+" .popup-message").appendChild(ul);
    }
}
function changeFile(action) {
    let fileObject = present_file;
    let bindings = {
        "del":function () {
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"Del_trash","id":fileObject.dataset.id},
                success:function (result) {
                    if(result.status==="success"){
                        fileObject.parentNode.removeChild(fileObject);
                        output("已彻底删除","#28d959");
                        if(!yn(".view-thumbnail li")){
                            yn(".view-thumbnail").innerHTML=`<div class="no-c"><img src="${window.present}/static/img/nofile.png" alt="没有文件"><p>回收站很干净</p></div>`;
                        }
                    }else {
                        output("删除失败","#ff3718");
                    }
                }
            })
        },
        "move-out-trash":function () {
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"Move_out_trash","id":fileObject.dataset.id},
                success:function (result) {
                    if(result.status==="success"){
                        fileObject.parentNode.removeChild(fileObject);
                        output("还原成功","#28d959");
                        if(!yn(".view-thumbnail li")){
                            yn(".view-thumbnail").innerHTML=`<div class="no-c"><img src="${window.present}/static/img/nofile.png" alt="没有文件"><p>回收站很干净</p></div>`;
                        }
                    }else {
                        output("还原失败","#ff3718");
                    }
                }
            })
        },
        "attribute":function () {
            let data =fileData[present_file.dataset.id];
            var pop = popup({
                title:"属性"
            });
            yn("#"+pop.id).className+=" file-attributes-w";
            let ul = document.createElement("ul");
            ul.setAttribute("class","file-attributes");
            let li = document.createElement("li");
            li.innerHTML=`<span>名称</span><input value='${data.name}' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            if(data.type==="dir"){
                li.innerHTML=`<span>类型</span><input value='文件夹' readonly/>`;
            }else{
                let index = data.name.lastIndexOf(".");
                let file_ext = data.name.substr(index+1);
                li.innerHTML=`<span>类型</span><input value='${file_ext} 文件' readonly/>`;
            }
            ul.appendChild(li.cloneNode(true));
            data.path = decodeURIComponent(data.path);
            let time = new Date(data.time);
            if(data.path==="/"){
                li.innerHTML=`<span>原位置</span><input value='根目录' readonly/>`;
            }else{
                li.innerHTML=`<span>原位置</span><input value='${data.path}' readonly/>`;
            }
            ul.appendChild(li.cloneNode(true));
            li.innerHTML=`<span>大小</span><input value='${data.size} MB' readonly/>`;
            ul.appendChild(li.cloneNode(true));
            if(data.type==="dir"){
                li.innerHTML=`<span>包含</span><input type="text" value="${data.dir_count} 文件夹 ${data.file_count} 文件" readonly/>`;
                ul.appendChild(li.cloneNode(true));
            }
            li.innerHTML=`<span>删除时间</span><input type="text" value="${time.getFullYear()}年${time.getMonth()}月${time.getDay()}日 ${time.getHours()}:${time.getMinutes()}" readonly/>`;
            ul.appendChild(li.cloneNode(true));
            yn("#"+pop.id+" .popup-message").appendChild(ul);
        },
        "paste":function () {
            var url = waitPaste.data.path;
            var name = waitPaste.data.name;
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"Move_in_trash","p":url,"append":name},
                success:function (result) {
                    if(result.status==="success"){
                        output('移至回收站成功','#28d959');
                        getNetdiskData();
                    }else {
                        output('移至回收站失败','#ff3718');
                    }
                    waitPaste={};
                }
            })
        },
        "cut":function () {
            waitPaste.action = 't_cut';
            waitPaste.id=fileObject.dataset.id;
            waitPaste.data = fileData[fileObject.dataset.id];
        },
        'del-all':function () {
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"Del_all_trash"},
                success:function (result) {
                    if(result.status==="success"){
                        output("清空回收站成功","#28d959");
                        yn(".view-thumbnail").innerHTML=`<div class="no-c"><img src="${window.present}/static/img/nofile.png" alt="没有文件"><p>回收站很干净</p></div>`;
                    }else {
                        output("清空失败","#ff3718");
                    }
                }
            })
        }
    };
    if(action in bindings){
        bindings[action]();
        closeMouseMenu();
        present_file=null;
    }
}
function getNetdiskData() {
    loaderBegin(yn(".view-thumbnail"));
    ajax({
        url:window.present+"/ajax.php",
        dataType:"json",
        data:{"action":"trash"},
        success:function (data) {
            if(typeof data!=='object'||data===null){
                console.log("读取失败");
                return;
            }
            if(data.length===0){return;}
            yn(".view-thumbnail").innerHTML="<ul></ul>";
            let temp = document.createElement("li");
            data.forEach(function (t) {
                fileData[t.trash_id]={
                    "type":t.original_type,
                    "dir_count":t.original_include_dir,
                    "file_count":t.original_include_file,
                    "name":t.original_name,
                    "path":t.original_path,
                    "size":t.original_size,
                    "time":t.trash_time
                };
                //获取后缀名
                let file_ext = "dir";
                if(t.original_type === "file"){
                    let index = t.original_name.lastIndexOf(".");
                    file_ext = t.original_name.substr(index+1);
                    file_ext = Controlgroup[file_ext]!==undefined?Controlgroup[file_ext]:file_ext;
                }
                temp.className="trash-file";
                temp.dataset.id=t.trash_id;
                temp.dataset.type=file_ext;
                temp.innerHTML=`
                <div class="thumbnail-img"></div>
                <span>${t.original_name}</span>
                `;
                yn(".view-thumbnail ul").appendChild(temp.cloneNode(true))
            })
        }
    });
    loaderFinish();
}
getNetdiskData();