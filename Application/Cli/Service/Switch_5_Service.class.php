<?php
/*
 * H3C S3100-52P操作模块
 */
namespace Cli\Service;
use \Cli\Model\TelnetModel;
class Switch_5_Service extends SwitchBaseService{
    public function __construct(TelnetModel $switch){
        parent::__construct($switch);
        $this->version_id=5;
    }

    /**
     * 重启交换机
     */
    public function reboot(){
        // TODO: Implement reboot() method.
    }
}