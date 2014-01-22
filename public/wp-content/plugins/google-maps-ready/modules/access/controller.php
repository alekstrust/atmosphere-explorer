<?php
class accessControllerGmp extends controllerGmp {
	
   public function saveIp() {
	   $res = new responseGmp();
		  if(($ipAddressData = $this->getModel()->saveIp(reqGmp::get('post'))) !== false) {
			$res->addMessage(langGmp::_('Ip address added'));
			$res->addData($ipAddressData);
		} else
			$res->pushError ($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function saveUser() {
	   $res = new responseGmp();
  		  if(($userData = $this->getModel()->saveUser(reqGmp::get('post'))) !== false) {
			$res->addMessage(langGmp::_('User added'));
			$res->addData($userData);
		} else
			$res->pushError ($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function deleteIp() {
	   $res = new responseGmp();
	   if(($delIpData = $this->getModel()->deleteElement(reqGmp::get('post'))) !== false) {
			$res->addMessage(langGmp::_('Ip address removed'));
			$res->addData($delIpData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function deleteUser() {
	   $res = new responseGmp();
	   if(($delUserData = $this->getModel()->deleteElement(reqGmp::get('post'))) !== false) {
			$res->addMessage(langGmp::_('User removed'));
			$res->addData($delUserData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
    public function saveRole() {
	   $res = new responseGmp();
	   if(($roleRetData = $this->getModel()->saveRole(reqGmp::get('post'))) !== false) {
			$res->addMessage(langGmp::_('Role change'));
			//$res->addData($roleRetData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();	
	}
   
}

