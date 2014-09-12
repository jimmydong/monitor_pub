<html>
<body>
<script>
<!--
var c_id=0;             //default: 0. you can change to a defined channel number
var sc=0;		//default: 0. you can change to 1 if it need not count to all
function UrlEncode(str){
    var ret="";var strSpecial="!\"#$%&()*+,/:;<=>?[]^`{|}~%";var tt="";
    for(var i=0;i<str.length;i++){ 
        var chr = str.charAt(i);var c=chr.charCodeAt(0).toString(16); 
        tt += chr+":"+c+"n"; 
        if(parseInt("0x"+c) > 0x7f){ 
            ret+="%"+c.slice(0,2)+"%"+c.slice(-2); 
        }else{ 
            if(chr==" ") 
                ret+="+"; 
            else if(strSpecial.indexOf(chr)!=-1) 
                ret+="%"+c.toString(16); 
            else 
                ret+=chr; 
        } 
    } 
    return ret; 
} 
try{var sh=window.screen.height;var sw=window.screen.width;var wh=document.body.clientHeight;var ww=document.body.clientWidth;var ua=navigator.userAgent;var tp=0;if(window.top=window.self)tp=1;}catch(e){}
document.write("<iframe width=600 height=400 border=0 src='http://monitor.yoka.com:81/pub/c.gif.php?c_id="+c_id+"&sc="+sc+"&tp="+tp+"&sh="+sh+"&sw="+sw+"&wh="+wh+"&ww="+ww+"&ua="+ua+"&rf="+UrlEncode(document.referrer)+"'/></iframe>");
-->
</script>
<noscript><img width=0 height=0 border=0 src=http://monitor.yoka.com:81/pub/c.gif.php?c_id=0/></noscript>
<a href=c.test.php?dddddd>test</a>
</body>
</html>
