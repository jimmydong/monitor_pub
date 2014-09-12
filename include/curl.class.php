<?php  
/**
* curl ������
* 
* @version 1.0.0
* @package curl
*/

class curl
{
    /**
    * ��ǰcurl�Ի�
    * @var resource
    * @access private
    */
    var $ch;
    
    /**
    * ��ǰ���͵ĵ�ַ
    *
    * @var string
    * @access private
    */
    var $url;
    
    /**
    * ������Ϣ
    *
    * @var string
    * @access private
    */
    var $debug; 
    
    /**
    * �����Ƿ����headerͷ
    *
    * @var integer
    * @access private
    */
    var $header = 0; 
    
    /**
    * ���캯��
    * @return void
    * @access public
    */
    function curl($url)
    {
        $this->url = $url;
    }
    
    /**
    * �趨�����Ƿ����header ͷ
    * @param integer $header 0��1,1������0������
    * @return void
    * @access public
    */
    function setHeader($header = 0)
    {
        $this->header = $header;
    }
    
    
    /**
    * ��ʼ��curl�Ի�
    * @param string $url ��ǰ���͵ĵ�ַ
    * @return boolean
    * @access private
    */
    function init()
    {
        $this->ch = @curl_init();
        if (!$this->ch)
        {
            return false;
        }
        $this->basic();
        return true;
    }
    
    /** 
    * ����ѡ��
    *
    * @return void
    * @access private
    */
    function basic()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_HEADER, $this->header);
    }

    /**
    * ����ѡ��
    *
    * @return void
    * @access public
    */
    function setOptions($options = array())
    {
        if (is_array($options))
        {
            foreach ($options as $key=>$value)    
            {
                $this->$key = $value;    
            }
        }
        
        //���HTTP���ش���300, �Ƿ���ʾ����
        if (isset($this->onerror) && $this->onerror)
        {
            curl_setopt($this->ch, CURLOPT_FAILONERROR, 1);    
        }
        
        //�Ƿ��з���ֵ
        if (isset($this->return) && $this->return == true && !isset($this->file)) 
        {
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        }
        
        //HTTP ��֤
        if (isset($this->username) && $this->username != "") 
        {
            curl_setopt($this->ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        }
        
        //SSL ���
        if (isset($this->sslVersion)) 
        {
            curl_setopt($this->ch, CURLOPT_SSLVERSION, $this->sslVersion);
        }
        if (isset($this->sslCert)) 
        {
            curl_setopt($this->ch, CURLOPT_SSLCERT, $this->sslCert);
        }
        if (isset($this->sslCertPasswd)) 
        {
            curl_setopt($this->ch, CURLOPT_SSLCERTPASSWD, $this->sslCertPasswd);
        }
        
        //���������
        if (isset($this->proxy))
        {
            curl_setopt($this->ch, CURLOPT_PROXY, $this->proxy);
        }
        if (isset($this->proxyUser) || isset($this->proxyPassword)) 
        {
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, "{$this->proxyUser}:{$this->proxyPassword}");
        }
        
        //��������
        if (isset($this->type)) 
        {
            switch (strtolower($this->type)) 
            {
                case "post":
                    curl_setopt($this->ch, CURLOPT_POST, 1);
                    break;
                case "put":
                    curl_setopt($this->ch, CURLOPT_PUT, 1);
                    break;
            }
        }        
        
        //�ϴ����
        if (isset($this->file)) 
        {
            if (!isset($this->filesize)) 
            {
                $this->filesize = filesize($this->file);
            }
            curl_setopt($this->ch, CURLOPT_INFILE, $this->file);
            curl_setopt($this->ch, CURLOPT_INFILESIZE, $this->filesize);
            curl_setopt($this->ch, CURLOPT_UPLOAD, 1);
        }
        
        //���ݷ���
        if (isset($this->fields)) 
        {
            if (!is_array($this->fields))
            {
                if (!isset($this->type))
                {
                    $this->type = "post";
                    curl_setopt($this->ch, CURLOPT_POST, 1);
                }
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->fields);
            }
            else 
            {
                if (!empty($this->fields))
                {
                    $p = array();
                    foreach ($this->fields as $key=>$value)
                    {
                        $p[] = $key . "=" . urlencode($value);
                    }
                    if (!isset($this->type))
                    {
                        $this->type = "post";
                        curl_setopt($this->ch, CURLOPT_POST, 1);
                    }
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, implode("&", $p));
                }        
            }
        }
        
        
        //�������
        if (isset($this->progress) && $this->progress == true) 
        {
            curl_setopt($this->ch, CURLOPT_PROGRESS, 1);
        }
        if (isset($this->verbose) && $this->verbose == true) 
        {
            curl_setopt($this->ch, CURLOPT_VERBOSE, 1);
        }
        if (isset($this->mute) && !$this->mute) 
        {
            curl_setopt($this->ch, CURLOPT_MUTE, 0);
        }

        //�������
        if (isset($this->followLocation))
        {
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $this->followLocation);
        }
        if (isset($this->timeout) && $this->timeout>0)
        {
            curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);    
        }
        else
        {
            curl_setopt($this->ch, CURLOPT_TIMEOUT, 5);
        }
        if (isset($this->connecttimeout) && $this->connecttimeout>0)
        {
            curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);    
        }
        else
        {
            curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 5);
        }
        if (isset($this->userAgent)) 
        {
            curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
        }
        
        //cookie ���
        if (isset($this->cookie)) 
        {
            $cookieData = "";
            foreach ($this->cookie as $name => $value) 
            {
                $cookieData .= urlencode($name) . "=" . urlencode($value) . ";";
            }
            curl_setopt($this->ch, CURLOPT_COOKIE, $cookieData);
        }
        
        //http ͷ
        if (isset($this->httpHeaders)) 
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpHeaders );
        }
    }    
        
    /**
    * ���÷��ؽ��ѡ������ؽ��
    * @return string ���ؽ��
    * @access public
    */
    function getResult()
    {
        $result = curl_exec($this->ch);
        return $result;
    }
    
    /**
    * �رյ�ǰcurl�Ի�
    * @return void
    * @access public
    */
    function close()
    {
        curl_close($this->ch);    
    }
    
    /**
    * �õ��Ի��в����Ĵ�������
    * @return string ��������
    * @access public
    */
    function getError()
    {
        return curl_error();    
    }
    
    /**
    * �õ��Ի��в����Ĵ����
    * @return integer �����
    * @access public
    */
    function getErrno()
    {
        return curl_errno();    
    }
    
    /**
    * �ж�ִ�У������������Ϣ
    * @param string $msg ������Ϣ
    * @return void
    * @access private
    */
    function halt($msg)
    {
        $message = "\n<br>��Ϣ:{$msg}";
        $message .= "\n<br>�����:".$this->getErrno();
        $message .= "\n<br>����:".$this->getError();
        echo $message;
        exit;
    }    
    
    /**
    * ������Ϣ
    * 
    * @return void
    * @access private
    */
    function debug()
    {
        $message .= "\n<br>�����:".$this->getErrno();
        $message .= "\n<br>����:".$this->getError();
        $this->debug = $message;
    }    
    
    /**
    * �����POST��ʽ���͵Ľ��
    * @param array/string $fields ���͵�����
    * @return string ���صĽ��
    * @access public
    */
    function post($fields = array())
    {
        $re = $this->init();
        if ($re){
            $this->setOptions(array("type"=>"post", "fields"=>$fields, "return"=>1, "onerror"=>1));
            $result = $this->getResult();
            $this->close();
            return $result;
        }else{
            return "";
        }
    }
    
    /**
    * �����GET��ʽ���͵Ľ��
    * @return string ���صĽ��
    * @access public
    */
    function get()
    {
        $re = $this->init();
        if ($re){
            $this->setOptions(array("return"=>1, "onerror"=>1));
            $result = $this->getResult();
            $this->close();
            return $result; 
        }else{
            return "";
        }   
    }
    
    /**
    * ��̬���ã������COOKIE��ʽ���͵Ľ��
    * @param string $url ���͵ĵ�ַ
    * @param array/string $fields ���͵�����
    * @return string ���صĽ��
    * @access public
    */
    function cookie($fields = array())
    {
        $re = $this->init();
        if ($re){
            $this->setOptions(array("cookie"=>$fields, "return"=>1, "onerror"=>1));
            $result = $this->getResult();
            $this->close();
            return $result; 
        }else{
            return "";
        }   
    }
    
    /**
    * ��̬���ã������FILE��ʽ���͵Ľ��
    * @param stream resource $file ���͵��ļ���
    * @param integer $filesize ���͵��ļ����Ĵ�С
    * @return string ���صĽ��
    * @access public
    function file($file, $filesize)
    {
        $re = $this->init();
        if ($re){
            $this->setOptions(array("type"=>"put", "file"=>$file, "filesize"=>$filesize, "return"=>1, "onerror"=>1));
            $result = $this->getResult();
            $this->close();
            return $result; 
        }else{
            return "";
        }     
    }
    */
    
    
}
?>
