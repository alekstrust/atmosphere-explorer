<?php
class gmapControllerGmp extends controllerGmp {
		
        public function getAllMaps($withMarkers=false){
           $maps = $this->getModel()->getAllMaps($withMarkers);
           return $maps;
        }	
        public function saveEditedMap(){
            $data = reqGmp::get('post');
            $res = new responseGmp();
            if(!isset($data['mapOpts']) || !isset($data['mapOpts']['id'])){
                $res->pushError(langGmp::_("Map not found"));
                return $res->ajaxExec();
            }
        
             $updateMap= $this->getModel()->updateMap($data['mapOpts']);
            if(!empty($data['markers'])){
                $updateMarkers = frameGmp::_()->getModule('marker')->
                            getModel()->updateMapMarkers($data['markers'],$data['mapOpts']['id']);
            }
            if($updateMap){
                $res->addMessage(langGmp::_("Done"));
            }else{
                $res->pushError(langGmp::_('Cannot save map'));
            }
            frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("map.edit");
            return $res->ajaxExec();
        }
        public function saveNewMap(){
            $res = new responseGmp();
            $data= reqGmp::get('post');
           
                $mapId= $this->getModel()->saveNewMap($data['mapOpts']);

                if($mapId){
                    if(isset($data['markers']) && !empty($data['markers'])){
                        
                        $addMarkers = frameGmp::_()->getModule('marker')->
                                getController()->saveMarkers($data['markers'],$mapId);
                        
                        if(!$addMarkers){
                            $res->pushError(langGmp::_('cannot add makrkers'));                            
                        }
                    }
                   $res->addMessage(langGmp::_('Done'));
                   $res->addData(array('map_id'=>$mapId));
                }else{
                    $res->pushError($this->getModel()->getErrors());
                }
            frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("map.save");                
          return $res->ajaxExec();
        }  
        
        public function removeMap(){
            $data=  reqGmp::get('post');
            $res = new responseGmp();
            if(!isset($data['map_id']) || empty($data['map_id'])){
                $res->pushError(langGmp::_("Nothing to remove"));
                return $res->ajaxExec();
            }
            
            if($this->getModel()->remove($data['map_id'])){
                $res->addMessage(langGmp::_("Done"));
            }else{
                $res->pushError($this->getModel()->getErrors());
            }
            frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("map.delete");            
            return $res->ajaxExec();
        }
        public function getMapsList(){
            $maps = $this->getModel()->getAllMaps(true);
            $data = $this->getView()->showAllMaps($maps,$fromAjax=true);
            $res= new responseGmp();
            $res->setHtml($data);
            return $res->ajaxExec();
        }
        
} 