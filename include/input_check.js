/*
all function list:
        function fucCheckLength(strTemp)
        function chkdate(datestr)
        function chkemail(a)
        function fucCheckNUM(NUM)
        function fucCheckTEL(TEL)
        function check_all()
*/
        //��������fucCheckLength
        //���ܽ��ܣ�����ַ����ĳ���
        //����˵����Ҫ�����ַ���
        //����ֵ������ֵ
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

        //��������chkdate        (YYYY-MM-DD)
        //���ܽ��ܣ�����Ƿ�Ϊ����
        //����˵����Ҫ�����ַ���
        //����ֵ��false����������  true��������
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
        
        //��������chkemail
        //���ܽ��ܣ�����Ƿ�ΪEmail Address
        //����˵����Ҫ�����ַ���
        //����ֵ��false������  true����
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
        
        //��������fucCheckNUM
        //���ܽ��ܣ�����Ƿ�Ϊ����
        //����˵����Ҫ��������
        //����ֵ��trueΪ�����֣�falseΪ��������
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
                        //˵�����ַ���������
                                return false;
                        }
                }
                //˵��������
                return true;
        }
        
        //��������fucCheckTEL
        //���ܽ��ܣ�����Ƿ�Ϊ�绰����
        //����˵����Ҫ�����ַ���
        //����ֵ��trueΪ�ǺϷ���falseΪ���Ϸ�
        function fucCheckTEL(TEL)
        {
                var i,j,strTemp;
                strTemp="0123456789-()\# ";
                for (i=0;i<TEL.length;i++)
                {
                        j=strTemp.indexOf(TEL.charAt(i));
                        if (j==-1)
                        {
                        //˵�����ַ����Ϸ�
                                return false;
                        }
                }
                //˵���Ϸ�
                return true;
        }

////////////////////////////////////////////////////////////////////
//
//	�ύ���麯�����
//	eg: <form name=form1 type=post action=# onsubmit="return check_all();">
//
///////////////////////////////////////////////////////////////////

	function check_all()
	{
		//�������������Ŀ��Ӧ��Ϊ��
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
			alert("����û����������ĵ��������("+erritem+")��\n�����ʱû�����ݣ�������'-'��");
			return false;
		}

		if (errcode==2)
		{
			alert("����û����������Ķ��������\n�����ʱû�����ݣ�������'-'��");
			return false;
		}

		if (errcode==3)
		{
			alert("���������б�û��ѡ��\n��ѡ�����ݡ�");
			return false;
		}
		
		return true;
	}

 function sclose()
 {
 	self.close();
 }