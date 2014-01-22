<?php
class adminmenuViewGmp extends viewGmp {
    protected $_file = '';
    /**
     * Array for standart menu pages
     * @see initMenu method
     */
    protected $_mainSlug = 'ready-google-maps';
    public function init() {
        $this->_file = __FILE__;
		//
        $this->_options = array(
            array('title' => langGmp::_('Add New Map'),	'capability' => 'manage_options', 'menu_slug' => 'ready-google-maps&tab=gmpAddNewMap',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            array('title' => langGmp::_('All Maps'), 'capability' => 'manage_options', 'menu_slug' => 'ready-google-maps&tab=gmpAllMaps', 'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            array('title' => langGmp::_('Markers'),		'capability' => 'manage_options', 'menu_slug' => 'ready-google-maps&tab=gmpMarkerList',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            array('title' => langGmp::_('Marker Groups'),		'capability' => 'manage_options', 'menu_slug' => 'ready-google-maps&tab=gmpMarkerGroups',	'function' =>  array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
            array('title' => langGmp::_('Plugin Settings'),		'capability' => 'manage_options', 'menu_slug' => 'ready-google-maps&tab=gmpPluginSettings',	'function' => array(frameGmp::_()->getModule('gmap')->getController(), 'getAllMaps')),
          
          
        );
        
       
        add_action('admin_menu', array($this, 'initMenu'), 9);
        parent::init();
    }
    public function initMenu() {
	$mainSlug = dispatcherGmp::applyFilters('adminMenuMainSlug', $this->_mainSlug);	
	$this->_options = dispatcherGmp::applyFilters('adminMenuOptions', $this->_options);
        add_menu_page(langGmp::_('Ready! Google Maps'),
                      langGmp::_('Ready! Google Maps'), 10,
                      $this->_mainSlug, 
                       array(frameGmp::_()->getModule('options')->getView(), 
                      'getAdminPage')
                    );
        
         foreach($this->_options as $opt) {
            add_submenu_page($mainSlug, langGmp::_($opt['title']),
                    langGmp::_($opt['title']), $opt['capability'], $opt['menu_slug'], $opt['function']);
        }
    }
    public function getFile() {
        return $this->_file;
    }
	public function getMainSlug() {
		return $this->_mainSlug;
	}
    /*public function getOptions() {
        return $this->_options;
    }*/
}