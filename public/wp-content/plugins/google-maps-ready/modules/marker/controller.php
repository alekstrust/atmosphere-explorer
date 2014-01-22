<?php
class markerControllerGmp extends controllerGmp {
	
        
    public function saveMarkers($markerArr=array(),$mapId=false){
        $res = new responseGmp();
        if(empty($markerArr) || !$mapId){
            $res->pushError(langGmp::_('Empty data'));
        }else{
            if($this->getModel()->saveMarkers($markerArr,$mapId)){
                return true;
            }else{
                $res->pushError($this->getModel()->getErrors());
            }
        }
       // frameGmp::_()->getModule("options")->updateStatistic("marker.save");
         frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("marker.save");        
        return $res->ajaxExec();
    }
    public function getMapMarkers($mapId=false){
        if(!$mapId){
            return false;
        }
        return $this->getModel()->getMapMarkers($mapId);
    }
    public function findaddress(){
        $data = reqGmp::get('post');
        $res = new responseGmp();
        $result = $this->getModel()->findAddress($data);
        if($result){
            $res->addData($result);
        }else{
            $res->pushError($this->getModel()->getErrors());
        }
        frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("geolocation.address.search");        
        return $res->ajaxExec();
    }
    public function removeMarker(){
        $params = reqGmp::get('post');
        $res =new responseGmp();
        if(!isset($params['id'])){
            $res->pushError(langGmp::_("Marker Not Found"));
            return $res->ajaxExec();
        }    
        if($this->getModel()->removeMarker($params["id"])){
           $res->addMessage(langGmp::_("Done")); 
        }else{
            $res->pushError(langGmp::_("Cannot remove marker"));
        }
       // frameGmp::_()->getModule("options")->updateStatistic("marker.delete");  
        frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("marker.delete");        
        return $res->ajaxExec();
    }
    public function refreshMarkerList(){
        $markers = $this->getModel()->getAllMarkers();
       
        $data = $this->getView()->showMarkersTab($markers,true);
        $res= new responseGmp();
        $res->setHtml($data);
        return $res->ajaxExec();
    }
    public function updateMarker(){
        $data = reqGmp::get("post");
     
        $res = new responseGmp();
        if(!isset($data['markerParams']) || !isset($data['markerParams']['id'])){
            $res->pushError(langGmp::_("Empty Marker"));
            return $res->ajaxExec();
        }
        if($this->getModel()->updateMarker($data['markerParams'])){
            $res->addMessage(langGmp::_("Done"));
        }else{
            $res->pushError(langGmp::_("Database Error."));
        }
        //frameGmp::_()->getModule("options")->updateStatistic("marker.edit");      
        frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("marker.edit");        
        return $res->ajaxExec();
    }
}