<?php
namespace Home\Controller;
use Org\Util\Rbac;
use Think\Exception;
use Think\Verify;

class LoginController extends PublicController{
    public function index(){
        $this->display();
    }

    public function login(){
        if(!IS_POST)$this->ajaxReturn(2);
        $ip=get_client_ip();
        $ban_times=S('ban_'.$ip);
        if($ban_times>5)$this->ajaxReturn(4);
        $username=I('post.username');
        $password=I('post.password');
        if($username==''||$password=='')$this->ajaxReturn(3);
        $verify=new Verify();
        if(!$verify->check(I('post.verify_code')))$this->ajaxReturn(5);
        $id=D('User')->checkPassword($username,$password);
        if($id==0){
            S('ban_'.$GLOBALS['ip'],$ban_times+1,900);
            $this->ajaxReturn(3);
        }
        D('User')->updateLoginTime($id);
        session(C('USER_AUTH_KEY'),$id);
        session('username',$username);
        import('ORG.Util.RBAC');
        RBAC::saveAccessList();
        $this->ajaxReturn(1,array('url'=>__APP__));
    }

    public function vcode(){
        $verify=new Verify();
        $verify->entry();
    }

    public function logout(){
        session('user_id',null);
        session('username',null);
        $this->success('将跳转到登录界面...','index');
    }

    public function changePassword(){
        if(IS_POST){
            $password=I('post.password');
            if(empty($password))$this->ajaxReturn(11);
            try{
                if(D('User')->changePassword($GLOBALS['user_id'],$password)){
                    session(null);
                    $this->ajaxReturn(1);
                }else{
                    $this->ajaxReturn(13);
                }
            }catch(Exception $e){
                $this->ajaxReturn(12);
            }
        }
    }
}