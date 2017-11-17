"use strict";
//保存更改
function saveThsiAccount() {
    yns(".personal-info-enter").forEach(function (t) {
        t.addEventListener("click",function () {
            let node = t.parentNode.querySelector("input");

        },false)
    })
}
// saveThsiAccount();
// 删除账号
function removeThisAccount() {
    popup({
        title:"删除账号",
        element:"你确定删除此账户",
        button:true,
        callback:function (status) {
            if(status){
                passwordVerification()
            }
        }
    });
    function passwordVerification() {
        popup({
            title:"密码验证",
            element:"删除账户需要验证密码,输入密码确认后将会删除此账户",
            button:true,
            input:true,
            inputType:'password',
            callback:function (value) {
                console.log(value);
            }
        });
    }
}
// 升级账户
function updateThisAccount() {
    popup({
        title:"升级账户",
        element:"你想升级储存空间?",
        button:true,
        callback:function (status) {
            if(status){
                alert("想得美");
                closePopup();
            }
        }
    });
}

// 个人设置 自动绑定连接 ajax获取处理
function autoload() {
    yns(".container-option>li").forEach(function (t) {
        t.addEventListener('click',ajaxLoadContent,false)
    });
}
autoload();
function ajaxLoadContent() {
    if(this.className.indexOf('selected')>0){return}
    loaderBegin(yn(".view"));
    var url = window.present+"/home/account/"+this.className;
    get(url)
}