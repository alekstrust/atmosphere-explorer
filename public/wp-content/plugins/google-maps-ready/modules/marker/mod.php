<?php
class  markerGmp extends moduleGmp {
	public function init() {
		dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherGmp::addAction('tplHeaderBegin',array($this,'showFavico'));
		dispatcherGmp::addAction('tplBodyEnd',array($this,'GoogleAnalitics'));
		dispatcherGmp::addAction('in_admin_footer',array($this,'showPluginFooter'));
                
	}
	public function addOptionsTab($tabs) {
		frameGmp::_()->addScript('adminMetaOptions', $this->getModPath(). 'js/admin.marker.js',array(),false,true);
		return $tabs;
	}
	public function getList() {
		return dispatcherGmp::applyFilters('metaTagsList', array(
			'meta_title' => array(
				'label'				=> 'Title',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getTitleOpts'),
			),
			'meta_desc' => array(
				'label'				=> 'Description',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getDescOpts'),
			),
			'meta_keywords' => array(
				'label'				=> 'Keywords',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getKeywordsOpts'),
			)
			,
			'google_analitics'=>array(
					'label'			=> 'Google Analitics Code',
					'optsTplEngine'	=>	array($this->getController()->getView(),
					'getGoogleAnaliticsOpts'),
			),
			'favico'=>array(
					'label'			=>	'Favico for site',
					'optsTplEngine'	=>	array($this->getController()->getView(),
					'getFavicoOpts'),				
			)
		));
	}
	
}