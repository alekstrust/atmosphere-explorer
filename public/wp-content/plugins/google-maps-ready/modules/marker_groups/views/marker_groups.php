<?php
class marker_groupsViewGmp extends viewGmp {

    public function showGroupsTab($groupsList,$isAjaxRequest=false){
       
        $this->assign('groupsList',$groupsList);
        if($isAjaxRequest){
           return parent::getContent('groupsTable');            
        }
        $this->assign("tableContent", parent::getContent('groupsTable'));
        //$marker_opts=  frameGmp::_()->getModule('marker')->getModel()->constructMarkerOptions();   
        //$this->assign('marker_opts' , $marker_opts);
        return parent::getContent('groupsList');                    
    }
}