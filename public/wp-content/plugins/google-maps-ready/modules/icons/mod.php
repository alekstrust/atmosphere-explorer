<?php
class  iconsGmp extends moduleGmp {
    public function init(){
        parent::init();
        frameGmp::_()->addScript('iconOpts', $this->getModPath() .'js/iconOpts.js');
    }
   
        
}