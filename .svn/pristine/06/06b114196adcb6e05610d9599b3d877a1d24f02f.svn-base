//写入cookies
function setCookie(name,value,second)
{
	//默认cookie过期时间为1天
	if(typeof(second)=="undefined"){
		second = 24*60*60;
	}
    var exp = new Date();
    exp.setTime(exp.getTime() + second*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)){
        return unescape(arr[2]);
	}else{
        return null;
	}
}

//删除cookies
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null){
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
	}
}