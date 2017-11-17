"use strict";
function yn(name) {if(!document.querySelector(name)){return false}return document.querySelector(name)}
HTMLElement.prototype.yn = function (name) {
    if(!this.querySelector(name)){return false}return this.querySelector(name)
};
function yns(name) {if(!document.querySelector(name)){return false}return document.querySelectorAll(name)}
HTMLElement.prototype.yns = function (name) {
    if(!this.querySelector(name)){return false}return this.querySelectorAll(name)
};

(function ($) {
    /*
    * 添加整数，包装在2 ^ 32。 这在内部使用16位操作
    * 解决一些JS解释器中的错误。
    */
    function safeAdd (x, y) {
        var lsw = (x & 0xffff) + (y & 0xffff)
        var msw = (x >> 16) + (y >> 16) + (lsw >> 16)
        return (msw << 16) | (lsw & 0xffff)
    }

    /*
    * 按位向左旋转32位数字。
    */
    function bitRotateLeft (num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt))
    }

    /*
    * 这些函数实现了算法使用的四个基本操作。
    */
    function md5cmn (q, a, b, x, s, t) {
        return safeAdd(bitRotateLeft(safeAdd(safeAdd(a, q), safeAdd(x, t)), s), b)
    }
    function md5ff (a, b, c, d, x, s, t) {
        return md5cmn((b & c) | (~b & d), a, b, x, s, t)
    }
    function md5gg (a, b, c, d, x, s, t) {
        return md5cmn((b & d) | (c & ~d), a, b, x, s, t)
    }
    function md5hh (a, b, c, d, x, s, t) {
        return md5cmn(b ^ c ^ d, a, b, x, s, t)
    }
    function md5ii (a, b, c, d, x, s, t) {
        return md5cmn(c ^ (b | ~d), a, b, x, s, t)
    }

    /*
    * 计算little-endian单词数组的MD5和一个位长度。
    */
    function binlMD5 (x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32);
        x[((len + 64) >>> 9 << 4) + 14] = len;

        var i;
        var olda;
        var oldb;
        var oldc;
        var oldd;
        var a = 1732584193;
        var b = -271733879;
        var c = -1732584194;
        var d = 271733878;

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = md5ff(a, b, c, d, x[i], 7, -680876936);
            d = md5ff(d, a, b, c, x[i + 1], 12, -389564586);
            c = md5ff(c, d, a, b, x[i + 2], 17, 606105819);
            b = md5ff(b, c, d, a, x[i + 3], 22, -1044525330);
            a = md5ff(a, b, c, d, x[i + 4], 7, -176418897);
            d = md5ff(d, a, b, c, x[i + 5], 12, 1200080426);
            c = md5ff(c, d, a, b, x[i + 6], 17, -1473231341);
            b = md5ff(b, c, d, a, x[i + 7], 22, -45705983);
            a = md5ff(a, b, c, d, x[i + 8], 7, 1770035416);
            d = md5ff(d, a, b, c, x[i + 9], 12, -1958414417);
            c = md5ff(c, d, a, b, x[i + 10], 17, -42063);
            b = md5ff(b, c, d, a, x[i + 11], 22, -1990404162);
            a = md5ff(a, b, c, d, x[i + 12], 7, 1804603682);
            d = md5ff(d, a, b, c, x[i + 13], 12, -40341101);
            c = md5ff(c, d, a, b, x[i + 14], 17, -1502002290);
            b = md5ff(b, c, d, a, x[i + 15], 22, 1236535329);

            a = md5gg(a, b, c, d, x[i + 1], 5, -165796510);
            d = md5gg(d, a, b, c, x[i + 6], 9, -1069501632);
            c = md5gg(c, d, a, b, x[i + 11], 14, 643717713);
            b = md5gg(b, c, d, a, x[i], 20, -373897302);
            a = md5gg(a, b, c, d, x[i + 5], 5, -701558691);
            d = md5gg(d, a, b, c, x[i + 10], 9, 38016083);
            c = md5gg(c, d, a, b, x[i + 15], 14, -660478335);
            b = md5gg(b, c, d, a, x[i + 4], 20, -405537848);
            a = md5gg(a, b, c, d, x[i + 9], 5, 568446438);
            d = md5gg(d, a, b, c, x[i + 14], 9, -1019803690);
            c = md5gg(c, d, a, b, x[i + 3], 14, -187363961);
            b = md5gg(b, c, d, a, x[i + 8], 20, 1163531501);
            a = md5gg(a, b, c, d, x[i + 13], 5, -1444681467);
            d = md5gg(d, a, b, c, x[i + 2], 9, -51403784);
            c = md5gg(c, d, a, b, x[i + 7], 14, 1735328473);
            b = md5gg(b, c, d, a, x[i + 12], 20, -1926607734);

            a = md5hh(a, b, c, d, x[i + 5], 4, -378558);
            d = md5hh(d, a, b, c, x[i + 8], 11, -2022574463);
            c = md5hh(c, d, a, b, x[i + 11], 16, 1839030562);
            b = md5hh(b, c, d, a, x[i + 14], 23, -35309556);
            a = md5hh(a, b, c, d, x[i + 1], 4, -1530992060);
            d = md5hh(d, a, b, c, x[i + 4], 11, 1272893353);
            c = md5hh(c, d, a, b, x[i + 7], 16, -155497632);
            b = md5hh(b, c, d, a, x[i + 10], 23, -1094730640);
            a = md5hh(a, b, c, d, x[i + 13], 4, 681279174);
            d = md5hh(d, a, b, c, x[i], 11, -358537222);
            c = md5hh(c, d, a, b, x[i + 3], 16, -722521979);
            b = md5hh(b, c, d, a, x[i + 6], 23, 76029189);
            a = md5hh(a, b, c, d, x[i + 9], 4, -640364487);
            d = md5hh(d, a, b, c, x[i + 12], 11, -421815835);
            c = md5hh(c, d, a, b, x[i + 15], 16, 530742520);
            b = md5hh(b, c, d, a, x[i + 2], 23, -995338651);

            a = md5ii(a, b, c, d, x[i], 6, -198630844);
            d = md5ii(d, a, b, c, x[i + 7], 10, 1126891415);
            c = md5ii(c, d, a, b, x[i + 14], 15, -1416354905);
            b = md5ii(b, c, d, a, x[i + 5], 21, -57434055);
            a = md5ii(a, b, c, d, x[i + 12], 6, 1700485571);
            d = md5ii(d, a, b, c, x[i + 3], 10, -1894986606);
            c = md5ii(c, d, a, b, x[i + 10], 15, -1051523);
            b = md5ii(b, c, d, a, x[i + 1], 21, -2054922799);
            a = md5ii(a, b, c, d, x[i + 8], 6, 1873313359);
            d = md5ii(d, a, b, c, x[i + 15], 10, -30611744);
            c = md5ii(c, d, a, b, x[i + 6], 15, -1560198380);
            b = md5ii(b, c, d, a, x[i + 13], 21, 1309151649);
            a = md5ii(a, b, c, d, x[i + 4], 6, -145523070);
            d = md5ii(d, a, b, c, x[i + 11], 10, -1120210379);
            c = md5ii(c, d, a, b, x[i + 2], 15, 718787259);
            b = md5ii(b, c, d, a, x[i + 9], 21, -343485551);

            a = safeAdd(a, olda);
            b = safeAdd(b, oldb);
            c = safeAdd(c, oldc);
            d = safeAdd(d, oldd);
        }
        return [a, b, c, d]
    }

    /*
    *将一串小尾数单词转换为一个字符串
    */
    function binl2rstr (input) {
        var i;
        var output = '';
        var length32 = input.length * 32;
        for (i = 0; i < length32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xff)
        }
        return output
    }

    /*
    * 将一个原始字符串转换为一个小端字的数组
    * 字符> 255的高字节被忽略。
    */
    function rstr2binl (input) {
        var i;
        var output = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0
        }
        var length8 = input.length * 8;
        for (i = 0; i < length8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xff) << (i % 32)
        }
        return output
    }

    /*
    * 计算原始字符串的MD5
    */
    function rstrMD5 (s) {
        return binl2rstr(binlMD5(rstr2binl(s), s.length * 8))
    }

    /*
    * 计算密钥和一些数据（原始字符串）的HMAC-MD5，
    */
    function rstrHMACMD5 (key, data) {
        var i;
        var bkey = rstr2binl(key);
        var ipad = [];
        var opad = [];
        var hash;
        ipad[15] = opad[15] = undefined;
        if (bkey.length > 16) {
            bkey = binlMD5(bkey, key.length * 8)
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5c5c5c5c;
        }
        hash = binlMD5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
        return binl2rstr(binlMD5(opad.concat(hash), 512 + 128))
    }

    /*
    * 将原始字符串转换为十六进制字符串
    */
    function rstr2hex (input) {
        var hexTab = '0123456789abcdef';
        var output = '';
        var x;
        var i;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hexTab.charAt((x >>> 4) & 0x0f) + hexTab.charAt(x & 0x0f)
        }
        return output
    }

    /*
    * 将字符串编码为utf-8
    */
    function str2rstrUTF8 (input) {
        return unescape(encodeURIComponent(input))
    }

    /*
    * 获取字符串参数并返回原始或十六进制编码的字符串
    */
    function rawMD5 (s) {
        return rstrMD5(str2rstrUTF8(s))
    }
    function hexMD5 (s) {
        return rstr2hex(rawMD5(s))
    }
    function rawHMACMD5 (k, d) {
        return rstrHMACMD5(str2rstrUTF8(k), str2rstrUTF8(d))
    }
    function hexHMACMD5 (k, d) {
        return rstr2hex(rawHMACMD5(k, d))
    }

    function md5 (string, key, raw) {
        if (!key) {
            if (!raw) {
                return hexMD5(string)
            }
            return rawMD5(string)
        }
        if (!raw) {
            return hexHMACMD5(key, string)
        }
        return rawHMACMD5(key, string)
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return md5
        })
    } else if (typeof module === 'object' && module.exports) {
        module.exports = md5
    } else {
        $.md5 = md5
    }
})(this);
function ajax(options) {
    options = options || {};
    options.type = (options.type || "GET").toUpperCase();//默认为get
    options.dataType = options.dataType || "text";//默认为text,请求数据格式
    options.async = options.async || true;//默认选择异步方式
    options.progress = options.progress || null;
    options.cors = options.cors || false;
    //判断是否是文件对象
    if(options.data instanceof FormData){
        var params = options.data;
        var randcode = String(Math.random()).replace("0.","");
        params.append('c',randcode);
    }else{
        var params = formatParams(options.data);
    }

    //创建
    if (window.XMLHttpRequest) {
        var xhr = new XMLHttpRequest();
    } else {
        var xhr = new ActiveXObject('Microsoft.XMLHTTP');
    }

    xhr.responseType = options.dataType;
    // 进度条
    if(options.progress!==null){
        xhr.upload.addEventListener("progress",options.progress,false)
    }

    //接收
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            var status = xhr.status;
            if (status >= 200 && status < 300) {
                options.success && options.success(xhr.response);
            } else {
                options.fail && options.fail(status);
            }
        }
    };
    //连接发送
    if (options.type === "GET") {
        xhr.open("GET", options.url + "?" + params, options.async);
        if(options.cors){setCors()}
        xhr.send();
    } else if (options.type === "POST") {
        xhr.open("POST", options.url, options.async);
        if(options.cors){setCors()}
        //设置表单提交时的内容类型
        if(params instanceof FormData){
            xhr.send(params);
        }else{
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(params);
        }
    }
    function setCors() {
        xhr.setRequestHeader("Access-Control-Allow-Origin", '*');
        xhr.setRequestHeader('X-Custom-Header', 'value');
    }
    //格式化数据
    function formatParams(data) {
        var arr = [];
        for (var name in data) {
            arr.push(encodeURIComponent(name) + "=" + encodeURIComponent(data[name]));
        }
        if(!options.cors){
            arr.push(("c=" + Math.random()).replace("0.",""));
        }
        return arr.join("&");
    }
}

function output(str,color) {
    color = color||'#00eaea';
    var outputDiv = document.createElement("div");
    outputDiv.className="point";
    outputDiv.innerText=str;
    outputDiv.style.backgroundColor=color;
    yn("body").appendChild(outputDiv);
    setTimeout(function () {
        yn("body").removeChild(outputDiv);
    },4000)
}
function loaderBegin(object) {
    if(yn(".loader")){
        yn(".loader").parentNode.removeChild(yn(".loader"));
    }
    var loader = document.createElement("div");
    loader.className="loader";
    object.appendChild(loader);
}
function loaderFinish() {
    if (!yn(".loader"))return;
    var loader = yn(".loader");
    loader.parentNode.removeChild(loader);
}
function setSiteTitle(str) {
    yn("head title").innerText=str;
}
var params = {
    left: 0,
    top: 0,
    currentX: 0,
    currentY: 0,
    flag: false
};
//获取相关CSS属性
var getCss = function(o,key){
    return o.currentStyle? o.currentStyle[key] : document.defaultView.getComputedStyle(o,false)[key];
};

//拖拽
var startDrag = function(bar, target, callback){
    if(getCss(target, "left") !== "auto"){
        params.left = getCss(target, "left");
    }
    if(getCss(target, "top") !== "auto"){
        params.top = getCss(target, "top");
    }
    //o是移动对象
    bar.onmousedown = function(event){
        if(target.className.indexOf("full")>0){
            return
        }
        params.flag = true;
        if(!event){
            event = window.event;
            //防止IE文字选中
            bar.onselectstart = function(){
                return false;
            }
        }
        var e = event;
        params.currentX = e.clientX;
        params.currentY = e.clientY;
    };
    document.addEventListener('mousemove',function (event) {
        var e = event ? event: window.event;
        if(params.flag){
            var nowX = e.clientX, nowY = e.clientY;
            var disX = nowX - params.currentX, disY = nowY - params.currentY;
            // 获取页面max宽度
            var dWidth = document.body.clientWidth-target.clientWidth/2;
            // 获取页面min宽度
            var cWidth = target.clientWidth/2;
            // 获取页面max高度
            var dHeight = document.body.clientHeight-target.clientHeight/2;
            // 获取页面min高度
            var cHeight = target.clientHeight/2;
            var x = parseInt(params.left) + disX;
            var y = parseInt(params.top) + disY;
            x = x<dWidth?x:dWidth;
            x = x>cWidth?x:cWidth;
            y = y<dHeight?y:dHeight;
            y = y>cHeight?y:cHeight;
            // console.log(target);
            target.style.left = x + "px";
            target.style.top = y + "px";
            if (event.preventDefault) {
                event.preventDefault();
            }
            return false;
        }

        if (typeof callback === "function") {
            callback(parseInt(params.left) + disX, parseInt(params.top) + disY);
        }
    },false);
    window.addEventListener('mouseup',function () {
        params.flag = false;
        if(getCss(target, "left") !== "auto"){
            params.left = getCss(target, "left");
        }
        if(getCss(target, "top") !== "auto"){
            params.top = getCss(target, "top");
        }
        document.removeEventListener('mousemove',function(){},false)
    },false);
};
//定义forEach
if(!("forEach" in Object)){
    Object.defineProperties(Object.prototype,{
        forEach:function (callback, thisArg) {
            var T, k;
            if (this == null) {
                throw new TypeError(' this is null or not defined');
            }
            var O = Object(this);
            var len = O.length >>> 0;
            if (typeof callback !== "function") {
                throw new TypeError(callback + ' is not a function');
            }
            if (arguments.length > 1) {
                T = thisArg;
            }
            k = 0;
            while (k < len) {
                var kValue;
                if (k in O) {
                    kValue = O[k];
                    callback.call(T, kValue, k, O);
                }
                k++;
            }
        }
    });
}
// 判断div是否可见
if(!("ishisdden" in HTMLElement.prototype)){
    Object.defineProperty(HTMLElement.prototype,'ishisdden',{
        get:function () {
            if(!(this instanceof HTMLElement)){
                throw new TypeError("警告: 不正常的使用");
            }
            if(this.style.display&&yn(".popup").style.display==="none"){
                return true;
            }
            if(this.style.visibility&&yn(".popup").style.visibility==="visibility"){
                return true;
            }
            if(this.offsetTop>document.body.clientHeight){
                return true;
            }
            if(this.offsetLeft>document.body.clientWidth){
                return true;
            }
            return false;
        }
    });
}
// 判断值是否在数组中
if(!("contains" in Array.prototype)){
    Object.defineProperties(Array.prototype,{
        contains:{value:function (obj) {
            var i = this.length;
            while (i--){
                if(this[i] === obj){
                    return true;
                }
            }
            return false;
        }}
    })
}
// 随机值
if(!("randomWord" in Math)){
    Object.defineProperties(Math,{
        randomWord:{value:function (min,max,_string) {
            _string = _string||false;
            max = max||"";
            var str = "",
                range = min,
                arr = "";
            if(_string){
                arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
            }else{
                arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
            }

            // 随机产生
            if(max!==min||max!==""){
                range = Math.round(Math.random() * (max-min)) + min;
            }
            for(var i=0; i<range; i++){
                var pos = Math.round(Math.random() * (arr.length-1));
                str += arr[pos];
            }
            return str;
        }}
    })
}
function popup(options) {
    options = options||{};
    options.button = options.button||false;
    options.input = options.input||false;
    options.inputType = options.inputType||"text";
    options.inputName = options.inputName||"popup-value";
    options.inputValue = options.inputValue||"";
    options.title = options.title||null;
    options.full = options.full||false;
    options.element = options.element||null;
    options.EnterValue = options.EnterValue||'确认';
    options.upload = options.upload||false;
    options.uploadType = options.uploadType||"file";
    options.textarea = options.textarea||false;
    options.textareaValue = options.textareaValue||"";
    var fileData = null;
    if (yn(".popup")){
        yn(".popup").parentNode.removeChild(yn(".popup"));
    }
    var popupDiv = yn("#popup").content.querySelector(".popup");
    popupDiv.querySelector(".popup-title").innerText=options.title;
    var cloneID = Math.randomWord(10,10,true);
    popupDiv.id=cloneID;
    yn(".view").appendChild(popupDiv.cloneNode(true));
    if(options.element!==null){
        document.querySelector("#"+cloneID+" .popup-message").innerHTML="<span class='text-el'>"+options.element+"</span>";
    }
    yn("#"+cloneID+" .popup-close").addEventListener('click',close,false);
    if(options.full){
        yn("#"+cloneID+" .popup-zoom").addEventListener('click',full,false);
    }else{
        yn("#"+cloneID+" .popup-zoom").parentNode.removeChild(yn("#"+cloneID+" .popup-zoom"));
    }
    startDrag(yn("#"+cloneID+" .popup-head"),yn("#"+cloneID));
    // 绑定浏览器变化
    window.addEventListener('resize',function () {
        if(yn(".popup").ishisdden){
         yn(".popup").removeAttribute("style");
        }
    },false);
    if(options.button){
        var popupButton = yn("#popup").content.querySelector(".popup-option");
        popupButton.querySelector(".popup-enter").innerText=options.EnterValue;
        yn("#"+cloneID+" .popup-message").appendChild(popupButton.cloneNode(true));
        yn("#"+cloneID+" .popup-enter").addEventListener("click",callback,false);
        yn("#"+cloneID+" .popup-cancel").addEventListener("click",close,false);
    }
    if(options.input&&options.button){
        var popupInput = yn("#popup").content.querySelector(".popup-input");
        yn(".popup-message").appendChild(popupInput.cloneNode(true));
        yn(".popup-input").type=options.inputType;
        yn(".popup-input").name=options.inputName;
        yn(".popup-input").value = options.inputValue;
    }
    if(options.textarea&&options.button){
        var textarea = yn("#popup").content.querySelector(".popup-area");
        yn(".popup-message").appendChild(textarea.cloneNode(true));
        yn(".popup-area").value=options.textareaValue;
        yn(".popup-option").style.margin="0rem auto";
    }
    if(options.upload&&options.button){
        var popupUpload;
        if(options.uploadType==="file"){
            popupUpload = yn("#popup").content.querySelector("#uploadFile");
        }else {
            popupUpload = yn("#popup").content.querySelector("#uploadFolder");
        }
        yn(".popup-message").appendChild(popupUpload.cloneNode(true));
        yn("#"+options.uploadType).addEventListener('change',function () {
            if(this.files.length===0){
                yn(".uploadBanner").innerText="未选择文件";
                return;
            }
            if(options.uploadType==='file'){
                yn(".uploadBanner").innerText="已选择"+this.files.length+"个文件";
            }else {
                yn(".uploadBanner").innerText="此文件共有"+this.files.length+"个文件";
            }
            if(this.files.length>=100){
                yn(".uploadBanner").innerText="文件数量超过100";
                return;
            }
            var size = 0;
            for (var i = 0;i<this.files.length;i++){
                size += this.files[i].size;
            }
            size = parseFloat(size/1024/1024).toFixed(2);
            if(size>64){
                yn(".uploadBanner").innerText="文件过大";
                return;
            }
            var file_list = this.files;
            var file_info_json="{";
            file_info_json+='"_folderRoot":"'+file_list[0].webkitRelativePath.split("/")[0]+'"';
            for (var k=0;k<file_list.length;k++){
                var temp = file_list[k].webkitRelativePath.split("/");
                // 移除第一个
                temp = temp.splice(1);
                var file_name = temp[temp.length-1];
                // 移除最后一个
                temp.pop();
                var file_url = temp.join("/");
                file_info_json+=',"'+file_name+'":"'+file_url+'"';
            }
            fileData = new FormData(yn(".upload-panel"));
            file_info_json+="}";
            fileData.append("file_info",file_info_json);
        },false)
    }
    function full() {
        var windowed = this.dataset.window;
        if(windowed==='1'){
            yn(".popup").removeAttribute("style");
            yn(".popup").className="popup popup-full";
            yn(".popup-zoom").dataset.window=0;
        }
        if(windowed==='0'){
            yn(".popup").className="popup popup-window";
            yn(".popup-zoom").dataset.window=1;
        }
    }
    function callback() {
        if(options.button){
            if(options.input){
                options.callback&&options.callback(yn(".popup-input").value);
                return
            }
            if(options.upload){
                if(fileData===null){
                    yn(".uploadBanner").innerText="未选择文件";
                    return;
                }
                options.callback&&options.callback(fileData);
                return;
            }
            if(options.textarea){
                options.callback&&options.callback(yn(".popup-area").value);
                return;
            }
            options.callback&&options.callback(true);
        }
    }
    function close() {
        yn(".popup").parentNode.removeChild(yn(".popup"));
    }
    return{
        id:cloneID
    }
}
function closePopup(){
    if(yn(".popup")){
        yn(".popup").parentNode.removeChild(yn(".popup"));
    }
}
//写入历史记录
function writeHistory(state) {
    if(history.state===null){
        // 0为浏览器的开始页面
        state.id=1;
        window.history.replaceState(state,state.title, state.url);
    }
    else if(state.url===location.href){
        state.id=history.state.id;
        window.history.replaceState(state,state.title, state.url);
    }
    else {
        state.id=history.state.id+1;
        window.history.pushState(state,state.title, state.url);
    }
}
//读取cookie
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)) return (arr[2]);
    else return null;
}