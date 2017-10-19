//use by index.html

scale = function (btn, bar, title) {  
	
      this.btn = document.getElementById(btn);  
      this.bar = document.getElementById(bar);  
      this.title = document.getElementById(title);  
      this.step = this.bar.getElementsByTagName("DIV")[0];  
      this.init();  
	  
    };  
	 
	var key = Math.ceil(Math.random()*1000000);
	 
	 
    scale.prototype = {  
		init: function () {  
		    var f = this, g = document, b = window, m = Math;  
		    f.btn.onmousedown = function (e) {  
				var x = (e || b.event).clientX;  
				var l = this.offsetLeft;  
				var max = f.bar.offsetWidth - this.offsetWidth;  
				g.onmousemove = function (e) {  
				 var thisX = (e || b.event).clientX;  
				 var to = m.min(max, m.max(-2, l + (thisX - x)));  
				 f.btn.style.left = to + 'px';  
				 f.ondrag(m.round(m.max(0, to / max) * 100), to);  
				 b.getSelection ? b.getSelection().removeAllRanges() : g.selection.empty();  
				};  
				g.onmouseup = new Function('this.onmousemove=null');  
			};  
		},  
	
	//拖放滑动条触发的事件
		ondrag: function (pos, x) {  
			
			this.step.style.width = Math.max(0, x) + 'px';  
			
			this.title.innerHTML = pos / 10 + '';  
			
			//拖动时计算number与key并发送到服务器端
			
			var number = parseInt(document.getElementById("title0").innerHTML)*10;
		   
			var img = document.getElementById('imgArea');
		
			img.innerHTML = "<img src='vcode.php?key="+key+"&number="+number+"' />";

		}  
		
} //scale end
	
new scale('btn0', 'bar0', 'title0');  
	 
	 
function getCookie(c_name){
	
	if (document.cookie.length>0){
		
		c_start=document.cookie.indexOf(c_name + "=");
		  
	if (c_start!=-1){ 
			
		c_start=c_start + c_name.length+1;
			
		c_end=document.cookie.indexOf(";",c_start);
			
		if (c_end==-1) c_end=document.cookie.length;
			
		return unescape(document.cookie.substring(c_start,c_end));
			
		} 
	}
		
	return ""
}
	

/* 定时器：用来输出验证成功，校验验证码是否成功，接收到cookie成功则2秒后输出内容 */
	
var validateTimer = setInterval(function(){
		
			if(getCookie('validate')=='succ'&&(document.getElementById("title0").innerHTML)!='0'){
			
				var text = document.getElementById('tips');
	
				tips.innerHTML = "<span style='font-size:20px;font-weight:bold;color:green;margin-left:25px;margin-top:200px'><i class='fa fa-check-circle'></i> 验证成功，SUCCESS</span>";
				
				var bar  = document.getElementById('bar');
				
				bar.innerHTML = "";
				
				var bar  = document.getElementById('infomation');
				
				bar.innerHTML = "";
				
				clearInterval(validateTimer);
			
			}
},1000);