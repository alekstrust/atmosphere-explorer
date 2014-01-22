<?php
class markerViewGmp extends viewGmp {
    
    public function showMarkersTab($markerList,$isAjaxRequest=false){
        $this->assign('markerList',$markerList);
        if($isAjaxRequest){
           return parent::getContent('markerTable');            
        }
        $this->assign("tableContent", parent::getContent('markerTable'));
        $marker_opts=  frameGmp::_()->getModule('marker')->getModel()->constructMarkerOptions();   
         $this->assign('marker_opts' , $marker_opts);
        return parent::getContent('markerList');                    

    }
}