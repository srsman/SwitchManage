<?php
namespace Home\Behaviors;
class VariablesBehavior extends \Think\Behavior{
    private $list=array(
        'Login',
    );

    public function run(&$param){
        $GLOBALS['ip']=get_client_ip();
        $GLOBALS['user_id']=intval(S('user_id'));
        echo $GLOBALS['user_id'];
        if($GLOBALS['user_id']>0){
            $GLOBALS['username']=S('username');
        }else{//未登录
            if(!$this->checkAccess())redirect(__APP__.'/Login');
        }
    }

    private function checkAccess(){
        foreach($this->list as &$val){
            if(is_array($val)){//精确到方法
                if(CONTROLLER_NAME==$val[0]&&ACTION_NAME==$val[1])return true;
            }else{//精确到控制器
                if(CONTROLLER_NAME==$val)return true;
            }
        }
        return false;
    }
}