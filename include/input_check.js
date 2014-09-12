/*
all function list:
        function fucCheckLength(strTemp)
        function chkdate(datestr)
        function chkemail(a)
        function fucCheckNUM(NUM)
        function fucCheckTEL(TEL)
        function check_all()
*/
        //函数名：fucCheckLength
        //功能介绍：检查字符串的长度
        //参数说明：要检查的字符串
        //返回值：长度值
        function fucCheckLength(strTemp)
        {
                var i,sum;
                sum=0;
                for(i=0;i<strTemp.length;i++)
                {
                        if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255))
                                sum=sum+1;
                        else
                                sum=sum+2;
                }
                return sum;
        }

        //函数名：chkdate        (YYYY-MM-DD)
        //功能介绍：检查是否为日期
        //参数说明：要检查的字符串
        //返回值：false：不是日期  true：是日期
        function chkdate(datestr)
        {
                var lthdatestr
                if (datestr != "")
                        lthdatestr= datestr.length ;
                else
                        lthdatestr=0;

                var tmpy="";
                var tmpm="";
                var tmpd="";
                //var datestr;
                var status;
                status=0;
                if ( lthdatestr== 0)
                        return false


                for (i=0;i<lthdatestr;i++)
                {        if (datestr.charAt(i)== '-')
                        {
                                status++;
                        }
                        if (status>2)
                        {
                                //alert("Invalid format of date!");
                                return false;
                        }
                        if ((status==0) && (datestr.charAt(i)!='-'))
                        {
                                tmpy=tmpy+datestr.charAt(i)
                        }
                        if ((status==1) && (datestr.charAt(i)!='-'))
                        {
                                tmpm=tmpm+datestr.charAt(i)
                        }
                        if ((status==2) && (datestr.charAt(i)!='-'))
                        {
                                tmpd=tmpd+datestr.charAt(i)
                        }

                }
                year=new String (tmpy);
                month=new String (tmpm);
                day=new String (tmpd)
                //tempdate= new String (year+month+day);
                //alert(tempdate);
                if ((tmpy.length!=4) || (tmpm.length>2) || (tmpd.length>2))
                {
                        //alert("Invalid format of date!");
                        return false;
                }
                if (!((1<=month) && (12>=month) && (31>=day) && (1<=day)) )
                {
                        //alert ("Invalid month or day!");
                        return false;
                }
                if (!((year % 4)==0) && (month==2) && (day==29))
                {
                        //alert ("This is not a leap year!");
                        return false;
                }
                if ((month<=7) && ((month % 2)==0) && (day>=31))
                {
                        //alert ("This month is a small month!");
                        return false;

                }
                if ((month>=8) && ((month % 2)==1) && (day>=31))
                {
                        //alert ("This month is a small month!");
                        return false;
                }
                if ((month==2) && (day==30))
                {
                        //alert("The Febryary never has this day!");
                        return false;
                }

                return true;
        }
        
        //函数名：chkemail
        //功能介绍：检查是否为Email Address
        //参数说明：要检查的字符串
        //返回值：false：不是  true：是
        function chkemail(a)
        {        var i=a.length;
                var temp = a.indexOf('@');
                var tempd = a.indexOf('.');
                if (temp > 1) {
                        if ((i-temp) > 3){
                                        if (tempd!=-1){
                                                return true;
                                        }

                        }
                }
                return false;
        }
        
        //函数名：fucCheckNUM
        //功能介绍：检查是否为数字
        //参数说明：要检查的数字
        //返回值：true为是数字，false为不是数字
        function fucCheckNUM(NUM)
        {
                var i,j,strTemp;
                strTemp="0123456789";
                if ( NUM.length== 0)
                        return false
                for (i=0;i<NUM.length;i++)
                {
                        j=strTemp.indexOf(NUM.charAt(i));
                        if (j==-1)
                        {
                        //说明有字符不是数字
                                return false;
                        }
                }
                //说明是数字
                return true;
        }
        
        //函数名：fucCheckTEL
        //功能介绍：检查是否为电话号码
        //参数说明：要检查的字符串
        //返回值：true为是合法，false为不合法
        function fucCheckTEL(TEL)
        {
                var i,j,strTemp;
                strTemp="0123456789-()\# ";
                for (i=0;i<TEL.length;i++)
                {
                        j=strTemp.indexOf(TEL.charAt(i));
                        if (j==-1)
                        {
                        //说明有字符不合法
                                return false;
                        }
                }
                //说明合法
                return true;
        }

////////////////////////////////////////////////////////////////////
//
//	提交检验函数框架
//	eg: <form name=form1 type=post action=# onsubmit="return check_all();">
//
///////////////////////////////////////////////////////////////////

	function check_all()
	{
		//检查所有输入项目不应该为空
		var coll = form1.all;
		var errcode = 0;
		if (coll!=null) 
		{
		    for (i=0; i<coll.length; i++) 
		    {
		        if( (coll.item(i).tagName=="INPUT")&&(coll.item(i).value=="") )
		        {
		        	if (coll.item(i).type!="radio" && coll.item(i).type!="hidden")
		        	{
		        	    errcode=1;erritem=coll.item(i).name;
		        	}
				}
				if( (coll.item(i).tagName=="TEXTAREA")&&(coll.item(i).value=="") )
		        {
		        	errcode=2;
				}
				if( (coll.item(i).tagName=="SELECT")&&(coll.item(i).value=="") )
				{
					errcode=3;
				}
			}
		}
		
		if (errcode==1)
		{
			alert("存在没有数据输入的单行输入框("+erritem+")。\n如果暂时没有数据，请输入'-'。");
			return false;
		}

		if (errcode==2)
		{
			alert("存在没有数据输入的多行输入框。\n如果暂时没有数据，请输入'-'。");
			return false;
		}

		if (errcode==3)
		{
			alert("存在下拉列表没有选择。\n请选择数据。");
			return false;
		}
		
		return true;
	}

 function sclose()
 {
 	self.close();
 }