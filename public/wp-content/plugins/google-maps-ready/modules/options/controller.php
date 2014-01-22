<?php
class optionsControllerGmp extends controllerGmp {
        public function activatePlugin() {
		$res = new responseGmp();
		if($this->getModel('modules')->activatePlugin(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Plugin was activated'));
		} else {
			$res->pushError($this->getModel('modules')->getErrors());
		}
		return $res->ajaxExec();
	}
	public function activateUpdate() {
		$res = new responseGmp();
		if($this->getModel('modules')->activateUpdate(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Very good! Now plugin will be updated.'));
		} else {
			$res->pushError($this->getModel('modules')->getErrors());
		}
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('save', 'saveGroup', 'saveBgImg', 'saveLogoImg','saveFavico', 
					'saveMainGroup', 'saveSubscriptionGroup', 'setTplDefault', 
					'removeBgImg', 'removeLogoImg',
					'activatePlugin', 'activateUpdate')
			),
		);
	}
        
        public function updateStatisticStatus(){
            $data = reqGmp::get("post");
            $result = $this->getModel("options")->updateStatisticStatus($data);
            $resp = new responseGmp();
            if($result){
                $resp->addMessage(langGmp::_("Done"));
            }else{
                $resp->pushError("Cannot Save Info");
            }
            return $resp->ajaxExec();
        }
        
}

