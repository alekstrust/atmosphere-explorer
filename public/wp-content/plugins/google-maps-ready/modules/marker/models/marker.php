<?php
class markerModelGmp extends modelGmp {
    public static $tableObj;
    function __construct(){
        if(empty(self::$tableObj)){
            self::$tableObj=frameGmp::_()->getTable('marker');  
        }
    }
    public function saveMarkers($markerArr,$mapId){
     
        foreach($markerArr as $marker){
             $marker['map_id']=$mapId;
             if(!isset($marker['marker_group_id'])){
                 $marker['marker_group_id']=1;
             }
             if(!isset($marker['icon'])){
                 $marker['icon']= 1;
             }
             unset($marker['id']);
             $marker['create_date']=date('Y-m-d H:i:s');
            if(!self::$tableObj->insert($marker)){
                $this->pushError(self::$tableObj->getErrors());
            }
        }

        return !$this->haveErrors();
    }
    public function updateMapMarkers($params,$mapId=null){
       
        foreach($params as $id=>$data){
            $exists = self::$tableObj->exists($id);
            unset($data['id']);
            if($mapId){
               $data['map_id']=$mapId;                
            }
            $data['marker_group_id']=$data['groupId'];
            if($exists){
                self::$tableObj->update($data," `id`='".$id."' ");
            }else{
                self::$tableObj->insert($data);                
            }
        }
        return true;
    }
    public function updateMarker($marker){
        
        $insert = array(
                        'marker_group_id'   =>  $marker['goup_id'],
                        'title'             =>  $marker['title'],
                        'address'           =>  $marker['address'],
                        'description'       =>  $marker['desc'],
                        'coord_x'           =>  $marker['position']['coord_x'],
                        'coord_y'           =>  $marker['position']['coord_y'],
                        'animation'         =>  $marker['animation'],
                        'icon'              =>  $marker['icon']['id']
        );
       
       return self::$tableObj->update($insert," `id`='".$marker['id']."'");
    }
    public function getMapMarkers($mapId){
        $markers = frameGmp::_()->getTable('marker')->get('*',array('map_id'=>$mapId));
        $iconsModel =  frameGmp::_()->getModule('icons')->getModel();
        foreach($markers as &$m){
            $m['icon'] =$iconsModel->getIconFromId($m['icon']);
        }
        return $markers;
        
    }
    public function constructMarkerOptions(){
        $params = array();
        $params['groups'] =  frameGmp::_()->getModule('marker_groups')->getModel()->getMarkerGroups();
        $params['icons']  =  frameGmp::_()->getModule('icons')->getModel()->getIcons();
        return  $params;
    }
    public function removeMarker($markerId){
       return self::$tableObj->delete(" `id`='".$markerId."' ");
    }
    public function findAddress($params){
        if(!isset($params['addressStr']) || strlen($params['addressStr'])<3){
            $this->pushError(langGmp::_('Address is empty or not match'));
            return false;
        }
        $addr = $params['addressStr'];
        $getdata = http_build_query(
            array(
                'address' => $addr,
                'language' => 'en',
             'sensor'=>'false',
             )
            );

        $google_response=  utilsGmp::jsonDecode(
                        file_get_contents(
                                'http://maps.googleapis.com/maps/api/geocode/json?'.$getdata));
        
        $res =array();

        foreach($google_response['results'] as $response){
            $res[]=array(
                        'position'  =>  $response['geometry']['location'],
                        'address'   =>  $response['formatted_address']
            );
        } 
        return $res;
    }
    public function removeMarkersFromMap($mapId){
        return frameGmp::_()->getTable('marker')->delete("`map_id`='".$mapId."'");
    }
    public function getAllMarkers(){
        $markerList = self::$tableObj->get("*");

        $iconsModel =  frameGmp::_()->getModule('icons')->getModel();
        $mapModel   =  frameGmp::_()->getModule('gmap')->getModel();   
        $markerGroupModel=  frameGmp::_()->getModule('marker_groups')->getModule()->getModel();
        foreach($markerList  as &$m){
            $m['icon'] =$iconsModel->getIconFromId($m['icon']);
            $m['map'] = $mapModel->getMapById($m['map_id'],false);
            $m['marker_group'] = $markerGroupModel->getGroupById($m['marker_group_id']);
        }        
        return $markerList;
    }
    public function showAllMarkers(){
        $markerList = $this->getAllMarkers();
        return $this->getModule()->getView()->showMarkersTab($markerList);
       }
}