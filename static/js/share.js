"use strict";
function judge_share(){
    if(!yn(".share-list tbody tr")){
        yn(".view-main").innerHTML=`<div class="no-c"><img src="${window.present}/static/img/nofile.png" alt="没有文件"><p>你没有分享文件</p></div>`;
    }
}
function remove_share() {
    if(!yn(".share-file-remove"))return;
    yns(".share-file-remove").forEach(function (t) {
        t.addEventListener("click",removeShare,false);
    });
    function removeShare() {
        let node = this;
        popup({
            title:"删除分享",
            element:"你确定删除分享?",
            button:true,
            callback:function(status){
                if(status){
                    save.open();
                    ajax({
                        url:window.present+"/ajax.php",
                        dataType:"json",
                        data:{"action":"un_share","key":node.parentNode.dataset.id},
                        success:function (result) {
                            node.parentNode.parentNode.removeChild(node.parentNode);
                            save.exit();
                            if(result.status==="success"){
                                output(result.code,"#28d959")
                            }else{
                                output(result.code,"#ff4901")
                            }
                            judge_share();
                        }
                    });
                    closePopup();
                }
            }
        });
    }
}
function change_share() {
    if(!yn(".share-file-password"))return;
    yns(".share-file-password input").forEach(function (t) {
        t.addEventListener("change",cg_passwd,false)
    });
    function cg_passwd(ev) {
        let node = ev.target;
        if(node.value.length<4||node.value.length>8){
            node.className="inconformity";
            node.title="最少4位,最大八位";
        }else {
            node.className="accord";
            node.title="私密分享的密钥";
        }
        node.parentNode.querySelector(".share-file-password-enter").addEventListener("click",function () {
            save.open();
            ajax({
                url:window.present+"/ajax.php",
                dataType:"json",
                data:{"action":"change_user_share_password","id":node.parentNode.parentNode.dataset.id,"value":node.value},
                success:function (result) {
                    save.exit();
                    if(result.status==="success"){
                        output(result.code,"#28d959")
                    }else{
                        output(result.code,"#ff4901")
                    }
                }
            });
        },false)
    }
}
var save = {
    "open":()=>{
        yn(".s-setting-save").className="s-setting-save clearfix";
    },
    "exit":()=>{
        if( yn(".s-setting-save")){
            yn(".s-setting-save").className="s-setting-save s-setting-close clearfix";
        }
    }
};

judge_share();
remove_share();
change_share();