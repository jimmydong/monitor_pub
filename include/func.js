////////////////////////////////////////////////////////////////////////
// show a help div.
// eg:
// <span onmouseover="show_help('mytestword');" onmouseout="show_help('');"> just a test for my script </span>
if(window.Event){
 window.constructor.prototype.__defineGetter__("event", function(){
  var o = arguments.callee.caller;
  var e;
  while(o != null){
   e = o.arguments[0];
   if(e && (e.constructor == Event || e.constructor == MouseEvent)) return e;
   o = o.caller;
  }
  return null;
 });
}
document.write("<div id=\"div_help\" style=\"position: absolute; width:400px;z-index: 300; visibility: hidden; filter: alpha(opacity=85);background: #ddeeff; border: 1pt solid steelblue;\">aaa</div>");
function show_help(help_string)
{
    eval("my_visibility=document.getElementById('div_help').style.visibility;");
    if (my_visibility=="hidden")
    {
        mX = (event.clientX ? event.clientX : event.pageX) + document.body.scrollLeft;
        eval("document.getElementById('div_help').style.pixelLeft = mX;");
		eval("document.getElementById('div_help').style.pixelTop = event.clientY + document.body.scrollTop - 10;");
		eval("document.getElementById('div_help').innerHTML=help_string;");
        eval("document.getElementById('div_help').style.visibility='visible';");
    }
    else
    {
        eval("document.getElementById('div_help').style.visibility='hidden';");
    }
    //alert(document.getElementById('div_help').innerHTML);
}