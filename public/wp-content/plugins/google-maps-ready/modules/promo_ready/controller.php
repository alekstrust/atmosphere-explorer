<?php
class promo_readyControllerGmp extends controllerGmp {
	public function welcomePageSaveInfo() {
		$res = new responseGmp();
                
		if($this->getModel()->welcomePageSaveInfo(reqGmp::get('post'))) {
			$res->addMessage(langGmp::_('Information was saved. Thank you!'));
			$originalPage = reqGmp::getVar('original_page');
			$returnArr = explode('|', $originalPage);
			$return = $this->getModule()->decodeSlug(str_replace('return=', '', $returnArr[1]));
			$return = admin_url( strpos($return, '?') ? $return : 'admin.php?page='. $return);
			$res->addData('redirect', $return);
			installerGmp::setUsed();
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		return $res->ajaxExec();
	}
	public function saveUsageStat() {
		$res = new responseGmp();
		$code = reqGmp::getVar('code');
		if($code)
			$this->getModel()->saveUsageStat($code);
		return $res->ajaxExec();
	}
	public function sendUsageStat() {
		$res = new responseGmp();
		$this->getModel()->sendUsageStat();
		$res->addMessage(langGmp::_('Information was saved. Thank you for your support!'));
		return $res->ajaxExec();
	}
	public function hideUsageStat() {
		$res = new responseGmp();
		$this->getModule()->setUserHidedSendStats();
		return $res->ajaxExec();
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('welcomePageSaveInfo', 'saveUsageStat', 'sendUsageStat', 'hideUsageStat')
			),
		);
	}
}