function move(corresponding) {
    var main = document.querySelector(".main");
    if(corresponding==='register'){
        // 注册
        main.style.transform="rotateY(130deg)";
        yns(".validate img")[1].src = window.present+"/CAPTCHA?"+Math.random();
    }else if(corresponding==='forget'){
        // 忘记密码
        main.style.transform="rotateY(-130deg)";
        yns(".validate img")[2].src = window.present+"/CAPTCHA?"+Math.random();
    }else{
        // 登录
        yns(".validate img")[0].src = window.present+"/CAPTCHA?"+Math.random();
        main.removeAttribute('style');
    }
}
function register() {
    var checkout = {};
    var rname = document.getElementsByName('rname')[0];
    var remail = document.getElementsByName('remail')[0];
    var rpass = document.getElementsByName('rpass')[0];
    var rvalidate = document.getElementsByName('rvalidate')[0];
    var regUser=/^[a-z0-9A-Z_\u4e00-\u9fa5]*$/;
    var regNum = /^[0-9]*$/;
    rname.onchange=function () {
        if(this.value.length<4||this.value.length>18){
            rname.className="input fail";
            rname.title="用户名长度不符合要求";
            checkout.rname = false;
            return;
        }
        if(!regUser.test(this.value)){
            rname.className="input fail";
            rname.title="用户名非法";
            checkout.rname = false;
            return;
        }
      //  验证用户名是否被使用
      ajax({
          url:"ajax.php",
          data:{"rname":this.value,"action":"checkUser"},
          success: function(response) {
              response = JSON.parse(response);
              if(response.status==="success"){
                  rname.className="input success";
                  rname.title=response.code;
                  checkout.rname = true;
              }else {
                  rname.className="input fail";
                  rname.title=response.code;
                  checkout.rname = false;
              }
          }
      });
    };
    rpass.onchange=function () {
      if(this.value.length<=3||this.value.length>=18){
          rpass.className="input fail";
          rpass.title="密码长度不符合要求";
          checkout.rpass = false;
          return;
      }
      if(regNum.test(this.value)){
          rpass.className="input fail";
          rpass.title="密码不能为纯数字";
          checkout.rpass = false;
          return;
      }
      checkout.rpass = true;
      rpass.className="input success";
      rpass.title="密码可以使用";
    };
    remail.onchange=function () {
        //  验证邮箱是否被使用
        ajax({
            url:"ajax.php",
            data:{"remail":this.value,"action":"checkEmail"},
            success: function(response) {
                response = JSON.parse(response);
                if(response.status==="success"){
                    remail.className="input success";
                    remail.title=response.code;
                    checkout.remail = true;
                }else {
                    remail.className="input fail";
                    remail.title=response.code;
                    checkout.remail = false;
                }
            }
        })
    };
    yn(".registerFrom").onsubmit=function () {
        loaderBegin(this);
        for (var u in checkout){
            if(!checkout[u]){
                return;
            }
        }
        var formdata = new FormData(this);
        formdata.set('rpass',md5(formdata.get('rpass')));
        ajax({
           url:this.action,
            type:"post",
            data:formdata,
            success: function(response) {
               console.log(response);
                response = JSON.parse(response);
                if(response.status==="success"){
                    yn(".registerTips").style.left="0px";
                    yn(".registerTips a").addEventListener("click",function () {
                        move();
                    },false);
               }else {
                    output(response.code)
                }
                loaderFinish();
            }
        });
        return false;
    }
}
function login() {
    yn(".loginFrom").onsubmit=function () {
        loaderBegin(this);
        var formdata = new FormData(this);
        formdata.set('upass',md5(formdata.get('upass')));
        ajax({
            url:this.action,
            type:"post",
            data:formdata,
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if(response.status==="success"){
                    output(response.code);
                    window.location = "index";
                }else if(response.status==="warn") {
                    activation(formdata.get('uname'),formdata.get('upass'),response.data);
                }else{
                    output(response.code);
                    yn(".validate input").value="";
                    yn(".validate img").src=window.present+"/CAPTCHA?"+Math.random();
                }
                loaderFinish();
            }
        });
        return false;
    }
}
function activation(user,pass,email) {
    yn(".activation").style.left="0px";
    yn("#CAPEMAIL").value=email;
    yn("#CAPEMAIL").onchange=function () {
        ajax({
            url:"ajax.php",
            data:{"action":"check_act","email":this.value,"user":user},
            dataType:"json",
            success:function (result) {
                if(result.status==="success"){
                    yn(".activation button").removeAttribute('disabled');
                    yn(".activation button").className="submit";
                    yn("#CAPEMAIL-warn").removeAttribute("style");
                }else{
                    yn(".activation button").setAttribute('disabled','disabled');
                    yn(".activation button").className="submit no-use";
                    yn("#CAPEMAIL-warn").style.display="block";
                }
            }
        })
    };
    yn(".activation a").addEventListener("click",function () {
        yn(".activation").style.left="360px";
        yn("#CAPEMAIL").value="";
    },false);
    yn(".activation .submit").onclick=function () {
        loaderBegin(yn(".activation"));
        var formdata = new FormData();
        formdata.append('user',user);
        formdata.append('pass',pass);
        formdata.append('email',yn("#CAPEMAIL").value);
        ajax({
            url:"login.php?a=activation",
            type:"post",
            data:formdata,
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if(response.status){
                    output(response.code);
                    var initTime = 60;
                    yn(".activation button").setAttribute('disabled','disabled');
                    yn(".activation button").className="submit no-use";
                    yn(".activation button").innerText=initTime+"s";
                    var interval = setInterval(function () {
                        initTime=initTime-1;
                        yn(".activation button").setAttribute('disabled','disabled');
                        yn(".activation button").className="submit no-use";
                        yn(".activation button").innerText=initTime+"s";
                        if(initTime===0){
                            clearInterval(interval);
                            yn(".activation button").removeAttribute('disabled');
                            yn(".activation button").className="submit";
                            yn(".activation button").innerText="重新发送";
                        }
                    },1000)
                }else{
                    output(response.code);
                }
                loaderFinish();
            }
        });
    }
}
function forget() {
    yn(".forgetFrom").onsubmit=function () {
        loaderBegin(this);
        ajax({
            url:this.action,
            type:"post",
            data:new FormData(this),
            success: function(response) {
                response = JSON.parse(response);
                if(response.status==="success"){
                    output(response.code);
                    window.location = window.present+"info";
                }else {
                    output(response.code);
                    yn(".forgetFrom img").src=window.present+"/CAPTCHA?"+Math.random();
                }
                loaderFinish();
            }
        });
        return false;
    }
}
login();
register();
forget();