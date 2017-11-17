// 绘制百分比
function spaceUseRatio() {
    if(!yn(".space-use-ratio")){return false}
    var elm = yn(".space-use-ratio");
    var scale = elm.dataset.scale;
    var canvas = yn(".space-use-ratio");
    var startAngle = -0.5*Math.PI;
    var finishAngle = 2*Math.PI*scale-.5*Math.PI;
    var percentage = (scale*100).toFixed(1)+"%";
    var fontSize = 20;
    canvas.width=208;
    canvas.height=208;
    var ctx = canvas.getContext('2d');
    ctx.lineWidth = 2;
    ctx.strokeStyle="#ccc";
    // 绘图
    ctx.beginPath();
    ctx.arc(104, 104, 100,startAngle, 2*Math.PI-.5*Math.PI);
    ctx.stroke();
    ctx.closePath();
    ctx.beginPath();
    ctx.strokeStyle="#3b77ea";
    ctx.arc(104, 104, 100,startAngle, finishAngle);
    ctx.stroke();
    ctx.closePath();
    // 绘字体
    ctx.fillStyle = '#666666';
    ctx.font= fontSize + 'px Microsoft Yahei';
    ctx.textAlign='center';
    ctx.fillText(percentage, 104, 104 + fontSize / 2-1);
}
spaceUseRatio();