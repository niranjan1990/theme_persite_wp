function $(e){return document.getElementById(e)}function showLetter(e){for(var t=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0"],n=0;n<t.length;n++)(null!=document.getElementById("let-"+t[n])||void 0!=document.getElementById("let-"+[n]))&&(document.getElementById(t[n]).style.display="none",document.getElementById("let-"+t[n]).removeAttribute("class"));document.getElementById(e).style.display="",document.getElementById("let-"+e).setAttribute("class","sel")}function showLetter1(e){for(var t=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0"],n=0;n<t.length;n++)(null!=document.getElementById("let1-"+t[n]+"xyz")||void 0!=document.getElementById("let1-"+t[n]+"xyz"))&&(document.getElementById(t[n]+"xyz").style.display="none",document.getElementById("let1-"+t[n]+"xyz").removeAttribute("class"));document.getElementById(e).style.display="",document.getElementById("let1-"+e+"xyz").setAttribute("class","sel")}function getHTTPObject(){return"undefined"==typeof XMLHttpRequest&&(XMLHttpRequest=function(){try{return new ActiveXObject("Msxml2.XMLHTTP.6.0")}catch(e){}try{return new ActiveXObject("Msxml2.XMLHTTP.3.0")}catch(e){}try{return new ActiveXObject("Msxml2.XMLHTTP")}catch(e){}return!1}),new XMLHttpRequest}