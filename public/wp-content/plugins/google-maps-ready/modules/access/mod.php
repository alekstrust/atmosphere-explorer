<?php
class accessGmp extends moduleGmp {
 public function init() {
		dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherGmp::addFilter('canAccessSite', array($this, 'accessFilter'));
	}
	
	public function addOptionsTab($tabs) {
		frameGmp::_()->addScript('adminAccessOptions', $this->getModPath(). 'js/admin.access.options.js');
		$tabs['gmpAccess'] = array(
		   'title' => 'Access', 'content' => $this->getController()->getView()->getAdminOptions(),
		);
		return $tabs;
	}  
	
	public function getList() {
			$res[] = $this->getController()->getView('ipBlock');
			$res[] = $this->getController()->getView('userBlock');
		return $res;
	}
	
	public function accessFilter() {
		return $this->getController()->getModel()->accessFilter();
	}
}

