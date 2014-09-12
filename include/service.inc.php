<?
/**
 * Xplus 1.3 版本
 * 服务提供模块
 *
 * 功能：                                
 *      根据不同服务类型，提供相应服务                 
 *
 * @author jimmy 
 * @version 2004.09.15
 * service_type: 参考 order_detail.od_type 
 * 其他 - 未定
 */

/**
 */
class CService
{
    
    /* public: configuration parameters */
    var $service_type       = 0;
    var $service_info       = array();
    var $user_id            = 0;
    
    /* public: result array and current row number */
    var $Result   = array();
    
    /* public: current error number and error text */
    var $Errno    = 0;
    var $Error    = "";
    
    /* private: link and query handles */
    var $q_usr;
    var $q_glb;
    
    function CService($service_type='',$service_info='')
    {
        if($service_type!='' && count($service_info))
            $this->init($service_type,$service_info);
    }

    function init($service_type,$service_info)
    {
        $this->service_type=$service_type;
        $this->service_info=$service_info;
        $this->q_usr = new DB_usr;
        $this->q_glb = new DB_glb;
    }
    
    function do_service()
    {
        switch($this->service_type)
        {
            case 1: //单一购买，授权码提供
                    $user_id=$od_info['user_id'];
                    $mag_id=$od_info['mag_id'];
                    $the_code=$this->generate_code($user_id,$mag_id);
                    $this->q_usr->fquery("INSERT INTO authcode SET code='$the_code', user_id='$user_id', mag_id='$mag_id'");
                    //... many thing to do
                    break;
            case 2: //包月购买，授权码提供
                    $mag_name_id=$od_info['mag_name_id'];
                    //.... many thing to do
                    break;
            default:$this->Error="";
                    $this->Errno=1;
                    return false;
                    break;
         }
    }
     
    function generate_code()
    {
        $str1   = 'jimmy';
        $str2   = 'jimmy';
        $str3   = 'test';
        $str4   = '12';
        $authen_code = exec("/usr/local/web2/AccreditID $str1 $str2 $str3 $str4");
        switch ($authen_code)
        {
            case "error00"    :   $this->Errno=1;$this->Error = "参数数量不够";break;
            case "error01"    :   $this->Errno=1;$this->Error = "参数1不合法";break;
            case "error02"    :   $this->Errno=1;$this->Error = "参数2不合法";break;
            case "error03"    :   $this->Errno=1;$this->Error = "参数3不合法";break;
            case "error04"    :   $this->Errno=1;$this->Error = "参数4不合法";break;
            case "error05"    :   $this->Errno=1;$this->Error = "授权码生成失败";break;
            case ""           :   $this->Errno=1;$this->Error = "授权失败，返回空码";break;
            default         :   return $authen_code;
        }
        return false;
    }

}