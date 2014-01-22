<?php
class optionsViewGmp extends viewGmp {
    public function getAdminPage() {
            if(!installerGmp::isUsed()){
                frameGmp::_()->getModule("promo_ready")->showWelcomePage();
                return;
            }
		$presetTemplatesHtml = $this->getPresetTemplates();

		$tabsData = array(
                        'gmpAddNewMap'   => array('title'    =>  'Add New Map',
                                                   'content' =>  $this->getAddNewMapData()),
                    
			'gmpAllMaps'     => array('title'   => 'All Maps', 
                                                  'content' => $this->getMapTemplateTab()),
                    
			'gmpMarkerList'  => array('title' => 'Markers',
                                                  'content' => $this->getMarkersTab()),
			
                        'gmpMarkerGroups'=> array('title'   => 'Marker Groups',
                                                  'content' => $this->getMarkersGroupsTab()),
			
                        'gmpEditMaps' => array('title' => 'Edit Maps', 
                                                'content' => $this->getMapsEditTab()),
                    
                        'gmpPluginSettings'=>array('title'=>'Plugin Settings',
                                                    'content'=>$this->getPluginSettingsTab())                        
		);
		$tabsData = dispatcherGmp::applyFilters('adminOptionsTabs', $tabsData);
	
		$this->assign('presetTemplatesHtml', $presetTemplatesHtml);
		$this->assign('tabsData', $tabsData);
                //$this->assign("admin_footer",frameGmp::_()->getModule("promo_ready")->displayAdminFooter());       
                $defaultOpenTab  = reqGmp::getVar("tab",'get');
                $this->assign("defaultOpenTab",$defaultOpenTab);
        parent::display('optionsAdminPage');
    }
    
        public function getPluginSettingsTab(){
            $saveStatistic = $this->getModel("options")->getStatisticStatus();
            $this->assign("saveStatistic",$saveStatistic);
            return parent::getContent("settingsTab");
        }
	public function getPresetTemplates() {
			return parent::getContent('templatePresetTemplates');
	}
        public function getAddNewMapData(){
            return frameGmp::_()->getModule('gmap')->getView()->addNewMapData();
        }
        public function getMapsEditTab(){
            return frameGmp::_()->getModule('gmap')->getView()->editMaps(); 
        }
        public function getMapTemplateTab() {
                $gmapModule = frameGmp::_()->getModule('gmap');
                $gmpAllMaps = $gmapModule->getController()->getAllMaps($withMarkers=true);
                
		if(!isset($this->optModel)){
			$this->assign('optModel', $this->getModel());
                }
                return $gmapModule->getView()->showAllMaps($gmpAllMaps);

	}
	public function getMarkersTab() {
		return  frameGmp::_()->getModule('marker')->getModel()->showAllMarkers();
	}
        public function getMarkersGroupsTab(){
		return  frameGmp::_()->getModule('marker_groups')->getModel()->showAllGroups();
        }
	public function getTemplateBgOptionsHtml() {
		if(!isset($this->optModel))
			$this->assign('optModel', $this->getModel());
		return parent::getContent('templateBgOptionsHtml');
	}
	public function getTemplateLogoOptionsHtml() {
		if(!isset($this->optModel))
			$this->assign('optModel', $this->getModel());
		return parent::getContent('templateLogoOptionsHtml');
	}
	public function getTemplateMsgOptionsHtml() {
		if(!isset($this->optModel))
			$this->assign('optModel', $this->getModel());
		return parent::getContent('templateMsgOptionsHtml');
	}
        public function displayDeactivatePage(){
            $this->assign('GET', reqGmp::get('get'));
            $this->assign('POST',reqGmp::get('post'));
            $this->assign('REQUEST_METHOD', strtoupper(reqGmp::getVar('REQUEST_METHOD', 'server')));
            $this->assign('REQUEST_URI', basename(reqGmp::getVar('REQUEST_URI', 'server')));
            parent::display("deactivatePage");
        }
}
