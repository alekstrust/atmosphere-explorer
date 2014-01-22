<?php
class  gmapGmp extends moduleGmp {
	public function init() {
                frameGmp::_()->addScript('gmp',GMP_JS_PATH."gmp.js",array(),false,false);
		dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherGmp::addAction('tplHeaderBegin',array($this,'showFavico'));
		dispatcherGmp::addAction('tplBodyEnd',array($this,'GoogleAnalitics'));
		dispatcherGmp::addAction('in_admin_footer',array($this,'showPluginFooter'));
                frameGmp::_()->addStyle('map_std', $this->getModPath() .'css/map.css');                
	}
	public function addOptionsTab($tabs) {
		frameGmp::_()->addScript('mapOptions', $this->getModPath(). 'js/admin.maps.options.js');
                frameGmp::_()->addScript('bootstrap', GMP_JS_PATH .'bootstrap.min.js');

                frameGmp::_()->addStyle('bootstrapCss', GMP_CSS_PATH .'bootstrap.min.css');
		return $tabs;
	}

	
        public function drawMapFromShortcode($params=null){
            frameGmp::_()->addStyle('gmapCss',$this->getModule()->getModPath()."css/map.css");
                    
            
            if(!isset($params['id'])){
                return $this->getController()->getDefaultMap();
            }
            return $this->getController()->getView()->drawMap($params);

        }
        public function welcomePageSaveInfo(){

        }
}