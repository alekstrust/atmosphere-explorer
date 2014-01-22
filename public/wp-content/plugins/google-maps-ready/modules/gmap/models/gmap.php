<?php
class gmapModelGmp extends modelGmp {
    public static $tableObj;
    function __construct(){
        if(empty(self::$tableObj)){
            self::$tableObj=frameGmp::_()->getTable('maps');  
        }
    }
    public function getAllMaps($onlyMaps=false){

        $maps = frameGmp::_()->getTable('maps')->get('*');
        foreach($maps as &$map){
            $markerModule = frameGmp::_()->getModule('marker');    
            $map['html_options']= utilsGmp::unserialize($map['html_options']);    
            $map['params'] =     utilsGmp::unserialize($map['params']);    
    
            if($onlyMaps){
              $map['markers']=$markerModule->getController()->getMapMarkers($map['id']);
            }
            
            
        }
        return $maps;
    }
    public function prepareParams($params){
          $htmlKeys=array('width','height','align','margin','border_color','border_width');
        $htmlOpts = array();
        foreach($htmlKeys as $k){
            $htmlOpts[$k]=isset($params[$k])?$params[$k]:null;
        }

        $mapOptKeys = array('enable_zoom','enable_mouse_zoom','zoom','type','language','map_display_mode','map_center');
        $mapOpts = array();
        foreach($mapOptKeys as $k){
            $mapOpts[$k]=isset($params[$k])?$params[$k]:null;
        }
        
        $insert = array(
            'title' => $params['title'],
            'description'  =>  $params['desc'],
            'html_options' =>  utilsGmp::serialize($htmlOpts),
            'params'       => utilsGmp::serialize($mapOpts)  
        );
        return $insert;
    }
    public function updateMap($params){
       $data = $this->prepareParams($params);
       return self::$tableObj->update($data," `id`='".$params['id']."' ");
       
    }
    public function saveNewMap($params){
        if(empty($params)){
            $this->pushError(langGmp::_('Empty Params'));
            return false;
        }
        $insert = $this->prepareParams($params);
     
         self::$tableObj =frameGmp::_()->getTable('maps');    
         $newMapId = self::$tableObj->store($insert,"insert");
         if($newMapId){
             return $newMapId;
         }else{
             $this->pushError($tbObj->getErrors());
             return false;
         }
     }
     public function remove($mapId){
         frameGmp::_()->getModule('marker')->getModel()->removeMarkersFromMap($mapId);
         return frameGmp::_()->getTable("maps")->delete($mapId);
         
         /*
          * remove map
          */
     }
     public function getMapById($id=false,$withMarkers=true){
         if(!$id){
             return false;
         }
        $map = frameGmp::_()->getTable('maps')->get('*',array('id'=>(int)$id));
        if(!empty($map)){
            $markerModule = frameGmp::_()->getModule('marker');    
            if($withMarkers){
               $map[0]['markers']=$markerModule->getController()->getMapMarkers($map[0]['id']);                
            }
            $map[0]['html_options']= utilsGmp::unserialize($map[0]['html_options']);                
            $map[0]['params']= utilsGmp::unserialize($map[0]['params']);                
            return $map[0];
        }
        return false;
     }
     public function constructMapOptions(){
         $params = array();
         $params['zoom']=array();
          for($i=0;$i<22;$i++){
                $params['zoom'][$i]=$i;
          }
          $params['map_type']= array('ROADMAP'=>'Map',
                                     'TERRAIN'=>'Relief',
                                     'HYBRID'=>'Hybrid',
                                     'SATELLITE'=>'Satellite'
                                    );
           
          $params['map_language']=array(
                                        'ar'=>'ARABIC',
                                        'bg'=>'BULGARIAN',
                                        'cs'=>'CZECH',
                                        'da'=>'DANISH',
                                        'de'=>'GERMAN',
                                        'el'=>'GREEK',
                                        'en'=>'ENGLISH',
                                        'en-AU'=>'ENGLISH (AUSTRALIAN)',
                                        'en-GB'=>'ENGLISH (GREAT BRITAIN)',
                                        'es'=>'SPANISH',
                                        'fa'=>'FARSI',
                                        'fil'=>'FILIPINO',
                                        'fr'=>'FRENCH',
                                        'hi'=>'HINDI',
                                        'hu'=>'HUNGARIAN',
                                        'id'=>'INDONESIAN',
                                        'it'=>'ITALIAN',
                                        'ja'=>'JAPANESE',
                                        'kn'=>'KANNADA',
                                        'ko'=>'KOREAN',
                                        'lv'=>'LATVIAN',
                                        'nl'=>'DUTCH',
                                        'no'=>'NORWEGIAN',
                                        'pt'=>'PORTUGUESE',
                                        'pt-BR'=>'PORTUGUESE (BRAZIL)',
                                        'pt-PT'=>'PORTUGUESE (PORTUGAL)',
                                        'rm'=>'ROMANSCH',
                                        'ru'=>'RUSSIAN',
                                        'sv'=>'SWEDISH',
                                        'zh-CN'=>'CHINESE (SIMPLIFIED)',
                                        'zh-TW'=>'CHINESE (TRADITIONAL)'              
          );
          $params['map_align'] = array('top'=> 'top','right'=>'right','bottom'=>'bottom','left'=>'left');
          $params['display_mode']=array('map'=>'Display Map','popup'=>'Display Map Icon');
          return $params;
     }
}
