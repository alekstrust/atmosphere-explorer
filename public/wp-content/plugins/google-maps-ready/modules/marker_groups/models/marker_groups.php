<?php
class marker_groupsModelGmp extends modelGmp {
    public static $tableObj;
    function __construct(){
        if(empty(self::$tableObj)){
            self::$tableObj=frameGmp::_()->getTable('marker_groups');  
        }
    }
    
    public function getMarkerGroups($params = array()){
        return self::$tableObj->get('*');
    }
    public function getGroupById($id){
        $group = self::$tableObj->get("*"," `id`='".$id."' ");
        if(!empty($group)){
            return $group[0];
        }
        return $group;
    }
    public function showAllGroups(){
        $groups = $this->getMarkerGroups();
        return $this->getModule()->getView()->showGroupsTab($groups);
    }
    public function saveGroup($params){
        if($params['mode']=="update"){
            unset($params['mode']);
            $id = $params['id'];
            unset($params['id']);
            frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("group.edit");
            return self::$tableObj->update($params," `id`='".$id."'");
        }else{
            unset($params['mode']);      
            frameGmp::_()->getModule("promo_ready")->getModel()->saveUsageStat("group.save");
            return self::$tableObj->insert($params);
        }
    }
    public function removeGroup($groupId){
      return self::$tableObj->delete(" `id`='".$groupId."' ");
    }
}