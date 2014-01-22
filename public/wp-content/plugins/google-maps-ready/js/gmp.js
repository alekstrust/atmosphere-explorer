var gmpActiveTab={};
var gmpExistsTabs=["gmpAddNewMap",'gmpAllMaps','gmpMarkerList','gmpMarkerGroups','gmpPluginSettings'];
var  gmpMapConstructParams={
             mapContainerId:"mapPreviewToNewMap"
     };
var nochange = false;     

 var def_tab_elem;




function gmpChangeTab(elem,sub){
    var tabId;
    
    try{
        tabId = elem.attr("href");
    }catch(e){
        tabId = elem;
    }
   
    if(gmpActiveTab.mainmenu=="#gmpEditMaps" && tabId!="#gmpEditMaps" && sub==undefined){
        gmpCancelMapEdit({changeTab:false});
    }
  
    if(gmpActiveTab.mainmenu=="#gmpAddNewMap" && tabId!="#gmpAddNewMap" && sub==undefined){
        if(gmpIsMapFormIsEditing()){
            if(confirm("If you leave tab,all information will be lost. \n Leave tab?")){
               return false; 
            }else{
                clearAddNewMapData();
                clearMarkerForm();
            }
        }
    }
    
    
    if(sub!= undefined){
       gmpActiveTab.submenu=tabId; 
    }else{
        if(tabId=="#gmpAddNewMap"){
           
            gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
        }
       gmpActiveTab.mainmenu=tabId;
    }    


    
    if(typeof(elem.tab)=='function'){
        elem.tab("show");        
    }
    
    switch(tabId){
        
        case "#gmpAddNewMap":
            currentMap = gmpMapsArr['mapPreviewToNewMap'];
        break;
        
        case "#gmpEditMaps":
            currentMap = gmpMapsArr["gmpEditMapsContainer"];
        break;
    
        case "#gmpMarkerList":
            currentMap = gmpMapsArr["gmpMapForMarkerEdit"];
        break;    
    }

}     
function toggleBounce(marker,animType) {
    
    if(animType==0){
        return false;   
    }

    if (marker.getAnimation() != null) {
      marker.setAnimation(null);
    } else if(animType==2) {	
	      marker.setAnimation(null);
    }else{
	      marker.setAnimation(google.maps.Animation.BOUNCE);
	}
}
function gmp_func_get_args() {
  if (!arguments.callee.caller) {
      return "";
  }
  return Array.prototype.slice.call(arguments.callee.caller.arguments);
}
function outGmp(){
    console.log(gmp_func_get_args());
}
function gmpGetEditorContent(){
        
    return tinyMCE.activeEditor.getContent();
}
function gmpSetEditorContent(content,editorId){
        tinyMCE.activeEditor.setContent(content);
}
jQuery(document).ready(function(){
  jQuery('.nav.nav-tabs  a').click(function (e) {
    e.preventDefault();

    
    var href = jQuery(this).attr("href");
    if(href.replace("#","")=='gmpAddNewMap'){
            if(jQuery("#mapPreviewToNewMap").html().length<150){
                gmpDrawMap(gmpMapConstructParams);
              gmpCancelMapEdit();
            }
     }

     if(jQuery(this).parents('ul').hasClass("gmpMainTab")){
         gmpChangeTab(jQuery(this));         
     }else{
         gmpChangeTab(jQuery(this),true);
     }


   })
   
    jQuery(".gmpMapOptionsTab a").click(function(e){
            e.preventDefault();        
    })
    /*
     *  jQuery('.nav.nav-tabs li.gmpAllMaps  a').tab('show');
     */

    
     if(jQuery("#mapPreviewToNewMap").length>0 &&  jQuery("#mapPreviewToNewMap").html().length<150){
                gmpDrawMap(gmpMapConstructParams);                        
      }
    
    jQuery(".gmpNewMapOptsTab a").click(function(e){
        jQuery(".gmpNewMapOptsTab a").removeClass("btn-primary");
        jQuery(this).addClass("btn-primary");
    })
    
  
    
    
    
    
    
    
          try{
                def_tab_elem = jQuery(".gmpMainTab  li."+defaultOpenTab).find('a');
                if(gmpExistsTabs.indexOf(defaultOpenTab) == -1){
                         def_tab_elem = jQuery(".gmpMainTab li."+gmpExistsTabs[0]).find('a')
                } 
           gmpChangeTab(def_tab_elem);            
        }catch(e){
            
        }

    
    
    
    
})
function gmpGetLicenseBlock(){
       return '<a style="color: rgb(68, 68, 68); text-decoration: none; cursor: pointer;margin-right: 2px;margin-left: -21px;background-color: rgba(255, 255, 255, 0.37);" href="http://readyshoppingcart.com/product/google-maps-plugin/" target="_blank">' +'Google Maps WordPress Plugin'+'</a>';

}
function gmpAddLicenzeBlock(){

    var befElem = jQuery('.gmnoprint').find('.gm-style-cc');
    befElem.css('float', 'right');
    befElem.css('width', '400px');
    befElem.find('a').after(gmpGetLicenseBlock());
}