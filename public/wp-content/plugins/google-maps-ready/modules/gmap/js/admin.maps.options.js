var currentMap;
var gmpTempMap;
var gmpMapsArr=[];
var gmpCurrentIcon=1;
var markerArr={}; 
var infoWindows=[];
var gmpNewMapOpts={};
var disableFormCheck=false;
var geocoder;
var markersToSend={};
var gmpDropDownObj={};
var gmpMapEditing=false;
var gmpMapForEdit;
var gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
var gmpAddMapForm ;
var gmpEditMapForm ;
var datatables={
    tables:{
        "GmpTableGroups"    :   "#GmpTableGroups",
        "gmpMapsListTable"         :   "#gmpMapsListTable",
        "GmpTableMarkers"   :   "#GmpTableMarkers"
    },
    createDatatables:function(){
        for(var i in this.tables){
            this.datatables[i] = jQuery(this.tables[i]).dataTable(this.default_options)
        }
        //jQuery(".dataTables_paginate").find('a').addClass("btn btn-info");
    },
    reCreateTable:function(tableSelector){
        this.datatables[tableSelector] = jQuery(this.tables[tableSelector]).dataTable(this.default_options);
    },
    datatables:{},
    default_options:{	
                    // "bJQueryUI": true,
            "iDisplayLength":7,
            "oLanguage": {
                            "sLengthMenu": "Display _MENU_ Orders In Page",
                            "sSearch": "Search:",
                            "sZeroRecords": "Not found",
                            "sInfo": "Show  _START_ to _END_ from _TOTAL_ records",
                            "sInfoEmpty": "show 0 to 0 from 0 records",
                            "sInfoFiltered": "(filtered from _MAX_ total records)",
                           
                            
                        },
                "bProcessing": true ,
                "bPaginate": true,
                "sPaginationType": "full_numbers"
     }
}


function gmpIsMapFormIsEditing(){
    if(disableFormCheck){
        return true;
    }
      var items={
        title : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_title").val(),        
        desc  : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_description").val(),
        margin : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_margin").val(),
        bwidth : jQuery("#gmpAddNewMapForm").find("#gmpNewMap_border_width").val(),
        mtitle : jQuery("#gmpAddMarkerToNewForm").find("#gmpNewMap_marker_title").val(),
        maddress : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_address").val(),
        mdesc : gmpGetEditorContent(),
        mcoord_x : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_x").val(),
        mcoord_y : jQuery("#gmpAddMarkerToNewForm").find("#gmp_marker_coord_y").val(),
     }
  
  for(var i in items){
      if(items[i]!="" || items[i].length>0){
         // console.log(i,items[i]);
            return true;
      }
  }
  return false;
}
function gmpClearMap(mapObj){
    if(typeof(mapObj)=="undefined"){
        mapObj = currentMap;
    }
   
    for(var i in markerArr){
        markersToSend[i]= markerArr[i];
        markersToSend[i].markerObj.setMap(null);
        markersToSend[i].markerObj=[];
        delete markerArr[i].markerObj;
        delete markerArr[i]["__e3_"];
    }
}
function getInfoWindow(title,content){
    var text="<div class='gmpMarkerInfoWindow'>";
        text+="<div class='gmpInfoWindowtitle'>"+title;
        text+="</div>";
        text+="<div class='gmpInfoWindowContent'>"+content;
        text+="</div></div>";
    return text;
}
function clearAddNewMapData(mapForm){
    if(mapForm==undefined){
        mapForm = jQuery("#gmpAddNewMapForm");
    }
    /*
     * clear map form
     */
    mapForm.clearForm();
    mapForm.find("#gmpNewMap_title").val("");
    mapForm.find("#gmpNewMap_description").val("");
    mapForm.find("#gmpNewMap_width").val("600");
    mapForm.find("#gmpNewMap_height").val("250");
    mapForm.find("#map_optsenable_zoom_check").trigger('click');
    
    mapForm.find("#map_optsenable_zoom_check").attr('checked','checked');
    mapForm.find("#map_optsenable_zoom_text").val(1);
    mapForm.find("#map_optsenable_mouse_zoom_check").attr('checked','checked');
    mapForm.find("#map_optsenable_mouse_zoom_text").val(1);
    mapForm.find("#gmpMap_zoom").val(8);    
    mapForm.find("#gmpMap_type").val('roadmap');    
    mapForm.find("#gmpMap_language").val('en');    
    mapForm.find("#gmpMap_align").val('top');    
    mapForm.find("input[name='map_opts[display_mode]']").each(function(){
        if(jQuery(this).val()=='map'){
            jQuery(this).attr('checked','checked');
        }
    })    
    
    /*
     * Clear marker form
     */
     jQuery("#gmpAddMarkerToNewForm").clearForm();
     mapForm.find("#gmpNewMap_marker_group").val(1);

       
        markerArr       =   new Object; 
        infoWindows     =   new Array();
        gmpNewMapOpts   =   new Object;
        
        jQuery("#gmpNewMap_title").css('border','');
}

function gmpGetRandomid(){
    var num = Math.random();
    num=""+num;
    var rand=num.substr(10);
    return 'id'+rand;
}

function gmpDrawMap(params){
    if(params.options !=undefined){
        var lat = params.options.center.lat;
        var lng = params.options.center.lng;
        var map_zoom =parseInt(params.options.zoom);
    }else{
        var lat = 40.1879714881;
        var lng = 44.5234475708;
        var map_zoom = 1;
    }
	
     var mapOptions = {
          center: new google.maps.LatLng(lat,lng),
          zoom: map_zoom,
        };
		
		if(typeof(params.mapTypeId)!="undefined"){
			mapOptions.mapTypeId=google.maps.MapTypeId[params.mapTypeId];
		}
		
        var map = new google.maps.Map(document.getElementById(params.mapContainerId),mapOptions);
		
           gmpTempMap = currentMap;
           currentMap = map;    
	 google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
             gmpAddLicenzeBlock();
         });
         
	google.maps.event.addListener(map, 'zoom_changed', function() {
            if(gmpAddMapForm.is(":visible")){
               gmpAddMapForm.find(".gmpMap_zoom").val(map.getZoom())                
            }
            if(gmpEditMapForm.is(":visible")){
               gmpEditMapForm.find(".gmpMap_zoom").val(map.getZoom())                
            }

        });
        
        gmpMapsArr[params.mapContainerId] = map;
        if(geocoder==undefined){
             geocoder = new google.maps.Geocoder();
        }
       
        return map;
}
function gmpEditMap(mapId){
    var editMap;
    if(typeof(existsMapsArr)=="undefined" || existsMapsArr.length<1){
        return false;
    }
    for(var i in existsMapsArr){
        if(existsMapsArr[i].id==mapId){
            editMap=existsMapsArr[i];
        }
    }
    
    jQuery(".gmpEditingMapName").html(editMap.title);
    jQuery(".gmpExistsMapOperations").show();
    jQuery("#gmpUpdateEditedMap").attr("onclick","gmpSaveEditedMap("+mapId+")")
    gmpChangeTab(jQuery('.nav.nav-tabs li.gmpEditMaps  a'));
    gmpMapEditing=true;
    markerArr = {};
    gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToEditMap");
    var mapParams ={
        gmpNewMap_title                 :editMap.title, 
        gmpNewMap_description           :editMap.description,
        gmpNewMap_width                 :editMap.html_options.width,
        gmpNewMap_height                :editMap.html_options.height,
        map_optsenable_zoom_em_text      :editMap.params.enable_zoom,
        map_optsenable_mouse_zoom_em_text:editMap.params.enable_mouse_zoom,
        gmpMap_zoom                     :editMap.params.zoom,
        gmpMap_type                     :editMap.params.type,
        gmpMap_language                 :editMap.params.language,
        gmpMap_align                    :editMap.html_options.align,
        gmpNewMap_margin                :editMap.html_options.margin,
        
        map_border_color                :editMap.html_options.border_color,
        gmpNewMap_border_width          :editMap.html_options.border_width
    }
    for(var id in mapParams){
        if(id=='map_border_color'){
            jQuery("#gmpEditMapForm").find("."+id).css('background-color',mapParams[id]);
            jQuery("#gmpEditMapForm").find("."+id).val(mapParams[id]);            
        }
        if(id=='map_optsenable_zoom_em_text'){
                jQuery("#"+id).val(mapParams[id]);
            
        }
        jQuery("#gmpEditMapForm").find("#"+id).val(mapParams[id]);
    }
    jQuery("#gmpEditMapForm").find("#map_display_mode").val("map");

    if(editMap.params.enable_mouse_zoom==1){
        jQuery("#gmpEditMapForm").find("#map_optsenable_mouse_zoom_check").attr("checked","checked");
    }else{
        jQuery("#gmpEditMapForm").find("#map_optsenable_mouse_zoom_check").removeAttr("checked");        
    }
    if(editMap.params.enable_zoom==1){
        
        jQuery("#gmpEditMapForm").find("#map_optsenable_zoom_check").attr("checked","checked");
    }else{
        jQuery("#gmpEditMapForm").find("#map_optsenable_zoom_check").removeAttr("checked");        
    }

        var newMapParams={
            mapContainerId:"gmpEditMapsContainer",
            options:{
                zoom:parseInt(editMap.params.zoom),
                center:{
                    lat:editMap.params.map_center.coord_y,
                    lng:editMap.params.map_center.coord_x
                },
				zoom	:parseInt(editMap.params.zoom)
             },
			mapTypeId:editMap.params.type
        }

        var gmpMapForEdit={
                mapParams   :   editMap,
                mapObj      :   gmpDrawMap(newMapParams)
        };
		gmpMapForEdit.mapObj.setZoom(newMapParams.options.zoom);

        var mapMarkers = [];
        for(var i in editMap.markers){
            editMap.markers[i].group_id =editMap.markers[i].marker_group_id ;
            editMap.markers[i].position={
                coord_x:editMap.markers[i].coord_x,
                coord_y:editMap.markers[i].coord_y,
            };
            drawMarker(editMap.markers[i]);   
        }
}

function gmpSaveEditedMap(mapId){

    var mapNewParams=getMapPropertiesFromForm(jQuery("#gmpEditMapForm"));
    mapNewParams.id=mapId;
    mapNewParams.border_color = jQuery("#gmpEditMapForm").find(".map_border_color").css("background-color");
    if(mapNewParams.title=='' || mapNewParams.title.length<3){
            alert("Map Title must be at least 3 chars");
            jQuery(".gmpTabForMapOpts").trigger("click");
            jQuery(".gmpeditMap_title").css("border", "1px solid red");
           return false;
        
    }
    
    for(var i in markerArr){
        delete markerArr[i].markerObj;
		if((markerArr[i].description =="" || typeof(markerArr[i].description)=="undefined")
                        && markerArr[i].desc!==""){
			markerArr[i].description = markerArr[i].desc;		
		}
        delete markerArr[i].desc;
    }
     var sendData={
        markers:markerArr,
        mapOpts:mapNewParams,
        mod    :'gmap',
        action :'saveEditedMap',
        reqType:'ajax'
    }

    jQuery.sendFormGmp({
        msgElID: 'gmpSaveEditedMapMsg',
        data:sendData,
        onSuccess:function(res){
           if(!res.error){
                //setTimeout(function(){
                 //   jQuery("ul.gmpMainTab  .gmpAllMaps a").trigger('click');
                  //  getMapsList();
                //},500)
           }
        }
    })
    
    jQuery(".gmpeditMap_title").css("border", "");
}
var paramObj;
function arrayUnique(param) {
    if(typeof(param.concat)=="undefined"){
       return "";
    }
    var a = param.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }
    if(a!="" || a!=" " || a!=","){
        return a;        
    }

};
function gmpFormatAddress(addressObj){

    var finishAddr=[];
    var count =0;
    var codes = ["street_address","route","administrative_area_level_1","country"];
    for(var i in addressObj){
        cur_addr= addressObj[i];
        switch(cur_addr.types[0]){
            
            case "neighborhood":
                if(cur_addr.types[1]=="political"){
                        finishAddr.push(cur_addr.address_components[0].long_name);
                 }
            
            break;

            case "route":
            case "street_address":
                    finishAddr.push(cur_addr.address_components[0].long_name);
                    finishAddr.push(cur_addr.address_components[1].long_name);
            break;
            case "sublocalit":
                if(cur_addr.types[1]=="political"){
                    finishAddr.push(cur_addr.address_components[0].long_name);
                }
            break;
            
            case "administrative_area_level_1":
                if(cur_addr.types[1]=="political"){
                    finishAddr.push(cur_addr.address_components[0].long_name);
                } 
            break;
            case "locality":
               
                if(cur_addr.types[1]=="political"){

                }
            break;
            case "country":
               
                if(cur_addr.types[1]=="political"){
                    finishAddr.push(cur_addr.address_components[0].long_name);
                }
            break;
        }
    }
    finishAddr = arrayUnique(finishAddr);
    return finishAddr.join(", ");
}
function getGmapMarkerAddress(params,markerId,ret,callback){
    var latlng = new google.maps.LatLng(params.coord_y,params.coord_x);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
            if(results.length>1){
                if(typeof(callback)!="undefined"){
                    var fAddress=gmpFormatAddress(results) ;
                    callback.func({
                        address:fAddress,
                        coord_x:params.coord_x,
                        coord_y:params.coord_y
                    });
                    return;
                }else if(ret!=undefined){
                    return results[1].formatted_address;
                }
                markerArr[markerId].address=results[1].formatted_address;
            }
        }else{
                markerArr[markerId].address="";            
        }
    });
}

function gmpRemoveMarkerObj(marker){
   
    if(confirm("Remove Marker?")){
       var sendData={
            id     : marker.id,
            mod    : 'marker',
            action : 'removeMarker',
            reqType: 'ajax'
        }
        jQuery.sendFormGmp({
            data:sendData,
            onSuccess:function(res){
                if(res.error){
                    alert(res.errors.join(","));
                }else{
                    marker.markerObj.setMap(null);
                    delete markerArr[marker.id];
                }
            }
        })
   }
}
function drawMarker(params){
   
   var iconId;
   var mIcon;

    if(typeof params.icon =="object"){
        mIcon=params.icon.path;
        iconId=params.icon.id
    }else{
         mIcon  = gmpExistsIcons[gmpCurrentIcon].path;   
         iconId = gmpCurrentIcon;            
    }
     var  markerIcon = {
            url: mIcon,
            //size: new google.maps.Size(32, 37),
            origin: new google.maps.Point(0,0),
	};
    var markerTitle='New Marker';
    var markerDesc='';
    var markerLatLng ;
    
    if(params.position == undefined){
         markerLatLng = currentMap.getCenter();             
    }else{
        markerLatLng = new google.maps.LatLng(params.position.coord_y,params.position.coord_x);
    }
    
    if(params.title!=undefined){
        markerTitle=params.title;
    }else{
        markerTitle="New Marker";
    }
    if(params.desc!=undefined){
        markerDesc=params.desc;
    }else if(params.description!="undefined"){
        markerDesc=params.description;        
    }
    
   if(params.id==undefined || params.id==""){
       var randId = gmpGetRandomid()+"";       
   }else{
       var randId = params.id
   }
     var animType = 2;
    if(params.animation==1){
         animType = 1;
    }
    markerItem = {
        title        : markerTitle,
        description  : markerDesc,
        id           : randId,
        coord_y      : markerLatLng.lat(),
        coord_x      : markerLatLng.lng(),
        icon         : iconId,
        groupId      : params.group_id,
        animation    : animType   
    };
    if(params.address!='undefined'){
        markerItem.address = params.address; 
    }


     markerArr[randId]=markerItem;
     
     markerArr[randId].markerObj=new google.maps.Marker({
                                        position    :   markerLatLng,
                                        icon        :   markerIcon,
                                        draggable   :   true,
                                        map         :   currentMap,
                                        title       :   markerTitle,
                                        zIndex      :   99999999,
                                        animation   :   animType,
                                        id          :   randId
                                   })
                    
    if(typeof(params.address)=='undefined' ||  params.address==""){
        getGmapMarkerAddress(markerItem,randId);
    }
     google.maps.event.addListener( markerArr[randId].markerObj, 'rightclick', function() {
           gmpRemoveMarkerObj(markerArr[randId]); 
     });
  
    google.maps.event.addListener(markerArr[randId].markerObj, 'dragend', function(e) {
        markerArr[this.id].coord_x=this.position.lng();
        markerArr[this.id].coord_y=this.position.lat();
        gmpChangeTab(gmpActiveTab.submenu,true)
        changeFormParams(this);
        editMarker(markerArr[randId]);

    });
    
    var countOfInfoWindows = infoWindows.length;    
        infoWindows[randId]= new google.maps.InfoWindow({
                              content   : getInfoWindow(markerTitle,markerDesc),
                              markerId  : randId 
                            });
    google.maps.event.addListener(markerArr[randId].markerObj, 'click', function(){
            for(var i in infoWindows){
                if(typeof(infoWindows[i].close)!='undefined'){
                   infoWindows[i].close();            
               }
               
            }
            if(typeof(infoWindows[randId].open)!='undefined'){
                infoWindows[randId].open(currentMap, markerArr[randId].markerObj);            
            }
            gmpChangeTab(gmpActiveTab.submenu,true)
            editMarker(markerArr[randId]);
            toggleBounce(markerArr[randId].markerObj,markerArr[randId].animation);
      });
      
  var bounds = new google.maps.LatLngBounds();
   for(var i in markerArr){
        var mLatLng = new google.maps.LatLng(markerArr[i].coord_y,markerArr[i].coord_x);
         bounds.extend (mLatLng);       
   }
  
    currentMap.fitBounds (bounds);
    if(currentMap.getZoom()>19){
        currentMap.setZoom(18);
    }
       if(params.address=="undefined" ||  params.address==""){
            getGmapMarkerAddress({
                         coord_x:markerItem.coord_x,
                         coord_y:markerItem.coord_y
                      },markerItem.id);        
    }
}
function changeFormParams(markerObj){
    var newAddress= getGmapMarkerAddress({
        coord_y:markerObj.position.lat(),
        coord_x:markerObj.position.lng()
    },"",true,{
        func:function(params){
            if(typeof(params.address)!="undefined"){
               gmpCurrentMarkerForm.find("#gmp_marker_address").val(params.address);
            }
            if(typeof(params.coord_x)!="undefined"){
               gmpCurrentMarkerForm.find("#gmp_marker_coord_x").val(params.coord_x);
            }
            if(typeof(params.coord_y)!="undefined"){
               gmpCurrentMarkerForm.find("#gmp_marker_coord_y").val(params.coord_y);
            }
        }
    });
 }
 
function editMarker(marker){
    var mapForm      = jQuery("#gmpAddNewMapForm");
    var markerForm   = jQuery("#gmpAddMarkerToNewForm");
//    var dropDownobj = gmpDropDownObj.mapForm;

    if(gmpMapEditing){
        mapForm     =   jQuery("#gmpEditMapForm");
        markerForm  =   jQuery("#gmpAddMarkerToEditMap");
//        dropDownobj =   gmpDropDownObj.mapEditForm;
    }
    
    markerForm.find("#gmpNewMap_marker_group").val(marker.groupId);
    markerForm.find("#gmpNewMap_marker_title").val(marker.title);
    markerForm.find("#gmp_marker_address").val(marker.address);
    
    gmpSetEditorContent(marker.description);
    
   
    markerForm.find("#gmp_marker_coord_x").val(marker.coord_x);
    markerForm.find("#gmp_marker_coord_y").val(marker.coord_y);
    if(marker.animation==1){
          markerForm.find("#marker_optsanimation_check").attr('checked','checked');        
    }
    markerForm.find("#marker_optsanimation_text").val(marker.animation);        
    
    var optIter=0;
        
    setcurrentIconToForm(marker.icon,markerForm)

//    markerForm.find("#gmpSelectedIcon").val(marker.icon);
//    markerForm.find(".markerIconItem.active").removeClass('active');
//    markerForm.find(".markerIconItem[data_val='"+marker.icon+"']").addClass('active');
//    
//  
//    markerForm.find("#gmpSelectedIcon").val(marker.icon);
//    gmpCurrentIcon=marker.icon;
    
    
    markerForm.find(".gmpAddMarkerOpts").hide();
    markerForm.find(".gmpEditMarkerOpts").show();
    markerForm.find(".gmpEditMarkerOpts").find("#gmpEditedMarkerLocalId").val(marker.id);
}

jQuery('#gmpMapHtmlOpts').submit(function(){
    gmpNewMapOpts.width     =   jQuery(this).find('#gmpNewMap_width').val();
    gmpNewMapOpts.height    =   jQuery(this).find('#gmpNewMap_height').val();    
    gmpNewMapOpts.classname =   jQuery(this).find('#gmpNewMapClassname').val();
    gmpNewMapOpts.title     =   jQuery(this).find('#gmpNewMap_title').val();
    gmpNewMapOpts.desc     =   jQuery(this).find('#gmpNewMap_description').val();

    jQuery('.gmpOptsCon').hide(300);
    return false;
})
var newShortcodePreview;
jQuery("#gmpSaveNewMap").click(function(){
    jQuery("#gmpAddNewMapForm").trigger("submit");    
})
function getMapPropertiesFromForm(formObj){
       if(formObj==undefined){
           formObj=jQuery("#gmpAddNewMapForm");
           var zoomId = "#map_optsenable_zoom_check";
           var zoomMouseId = "#map_optsenable_mouse_zoom_check";
           
       } else{
           var zoomId = "#map_optsenable_zoom_em_text";
           var zoomMouseId = "#map_optsenable_mouse_zoom_em_text";           
       }
     var gmpNewMapOpts={
                title               :   formObj.find("#gmpNewMap_title").val(),
                desc                :   formObj.find("#gmpNewMap_description").val(),
                width               :   formObj.find("#gmpNewMap_width").val(),
                height              :   formObj.find("#gmpNewMap_height").val(),
                zoom                :   formObj.find("#gmpMap_zoom").val(),
                type                :   formObj.find("#gmpMap_type").val(),
                language            :   formObj.find("#gmpMap_language").val(),
                align               :   formObj.find("#gmpMap_align").val(),
                margin              :   formObj.find("#gmpNewMap_margin").val(),
                border_color        :   formObj.find(".map_border_color").val(),
                border_width        :   formObj.find("#gmpNewMap_border_width").val(),
    }
    
    if(formObj.attr('id')=="gmpAddNewMapForm"){
        gmpNewMapOpts.enable_zoom         =   formObj.find("#map_optsenable_zoom_text").val();
        gmpNewMapOpts.enable_mouse_zoom   =   formObj.find("#map_optsenable_mouse_zoom_text").val();       
    }else{
        gmpNewMapOpts.enable_zoom         =   formObj.find("#map_optsenable_zoom_em_text").val();
        gmpNewMapOpts.enable_mouse_zoom   =   formObj.find("#map_optsenable_mouse_zoom_em_text").val();       
    }
    
    
    gmpNewMapOpts.map_center={
               coord_x:currentMap.getCenter().lng(),
               coord_y:currentMap.getCenter().lat(),
            };             
   
          gmpNewMapOpts['map_display_mode']="map";
          
    
    return gmpNewMapOpts;
}
jQuery("#gmpAddNewMapForm").submit(function(){
     var gmpNewMapOpts = getMapPropertiesFromForm();
       if(gmpNewMapOpts.title=="" || gmpNewMapOpts.title.length<3){
            jQuery("#gmpTabForNewMapOpts").trigger('click');
            jQuery("#gmpNewMap_title").css('border','1px solid red')
            alert("Map Title must be at least 3 chars");
            return false;
        }
    gmpClearMap();
    var sendData={
        markers:markerArr,
        mapOpts:gmpNewMapOpts,
        mod    :'gmap',
        action :'saveNewMap',
        reqType:'ajax'
    }

    jQuery.sendFormGmp({
	msgElID: 'gmpNewMapMsg',
        data:sendData,
        onSuccess: function(res) {
            if(!res.error){
            var newShortcodePreview  = "<pre class='gmpPre'>";
                newShortcodePreview += "<h3>New Shortcode</h3>";
                newShortcodePreview += "<h5>Paste this shortcode to preview created map in site</h5>";
                newShortcodePreview +=" [ready_google_map id='"+res.data.map_id+"']";
                newShortcodePreview += "</div></pre>";
                getMapsList({
                            'id':res.data.map_id
                        });
                clearAddNewMapData();
            }
        }
    })
    
    return false;
})

function clearMarkerForm(markerForm){
    if(markerForm==undefined){
        var markerForm=jQuery("#gmpAddMarkerToNewForm");
    }
    markerForm.find("#gmpNewMap_marker_group").val(1);
    markerForm.find("#gmpNewMap_marker_title").val("");
    markerForm.find("#gmp_marker_address").val("");
    try{
     tinyMCE.activeEditor.setContent(" ");        
    }catch(e){
       
    }

    markerForm.find("#gmp_marker_coord_x").val("");
    markerForm.find("#gmp_marker_coord_y").val("");
    markerForm.find("#gmpIconUrlToDown").val("");
    markerForm.find("#marker_optsanimation_text").val("0");
    markerForm.find("#marker_optsanimation_check").removeAttr('checked');
    markerForm.find("#marker_optsanimation_check").removeAttr('checked');
    markerForm.find(".gmpAddressAutocomplete ul").empty();
    
}
function updateMarker(newParams,markerForm,leaveForm){
    
    var currentMarker = markerArr[newParams.id];
    if(typeof(markerForm)=='undefined'){
       var markerForm   = jQuery("#gmpAddMarkerToNewForm");        
     }          

      if(gmpMapEditing){
            markerForm  =   jQuery("#gmpAddMarkerToEditMap");
       }

    if(newParams.animation==1){
        currentMarker.markerObj.animation=google.maps.Animation.BOUNCE
    }
    currentMarker.animation=newParams.animation;

    for(var i in newParams){
        if(typeof(currentMarker.i)!=undefined){
            currentMarker[i]=newParams[i];
        } 
    }
    currentMarker.markerObj.setIcon(gmpExistsIcons[currentMarker.icon].path);
    currentMarker.markerObj.title  =  currentMarker.title;
    if(currentMarker.coord_x!="" && currentMarker.coord_y!=""){
       currentMarker.markerObj.setPosition(new google.maps.LatLng(currentMarker.coord_y,currentMarker.coord_x));
    }

    markerArr[newParams.id]=currentMarker;
    
    for(var i in infoWindows){
        if(newParams.id==infoWindows[i]["markerId"]){
            infoWindows[i].setContent(getInfoWindow(newParams.title,newParams.description));
        }
    }
    if(typeof(leaveForm)=='undefined'){
        markerForm.find("#gmpEditedMarkerLocalId").val("");
        clearMarkerForm(markerForm);        
        markerForm.find(".gmpEditMarkerOpts").hide();
        markerForm.find(".gmpAddMarkerOpts").show();
    }

    var bounds = new google.maps.LatLngBounds();
    for(var i in markerArr){
         var mLatLng = new google.maps.LatLng(markerArr[i].coord_y,markerArr[i].coord_x);
          bounds.extend (mLatLng);       
    }
    currentMap.fitBounds(bounds);
    if(currentMap.getZoom()>19){
        currentMap.setZoom(18);
    }
   
}
function afterMarkerFormSubmit(formObj){
      var markerParams = {
           title     : formObj.find("#gmpNewMap_marker_title").val(),
           desc      : tinyMCE.activeEditor.getContent(), 
           group_id  : formObj.find("#gmpNewMap_marker_group").val(),
           animation : formObj.find('#marker_optsanimation_text').val(),
        };
       
        markerParams.icon=formObj.find("#gmpSelectedIcon").val();
        
        var lat = formObj.find("#gmp_marker_coord_y").val();
        var lng = formObj.find("#gmp_marker_coord_x").val();
        if(lat!="" && lng!=""){
           markerParams.position={
            coord_x:parseFloat(lng),
            coord_y:parseFloat(lat)
         }
        }
       

        drawMarker(markerParams);
        clearMarkerForm(formObj);     
        return false;
}
jQuery(document).ready(function(){
    jQuery("li.ui-state-default.ui-corner-left").each(function(){
        if(jQuery(this).hasClass('ui-tabs-active')){
            var id = jQuery(this).find('a').attr('href').replace("#","");
            activeTab['id']=id;
          }
    })
       
    jQuery("#gmpHideNewMapPreview").click(function(){
        jQuery("#mapPreviewToNewMap").toggle();
        if(jQuery("#mapPreviewToNewMap").is(":visible")){
            jQuery("#gmpHideNewMapPreview").html("Hide Map Preview");
        }else{
            jQuery("#gmpHideNewMapPreview").html("Show Map Preview");
        }
    })
    jQuery("#gmpAddMarkerToNewForm").submit(function(){
        return afterMarkerFormSubmit(jQuery(this));
    })
    jQuery("#gmpAddMarkerToEditMap").submit(function(){
        return afterMarkerFormSubmit(jQuery(this));        
    })
    jQuery(".gmpCancelMarkerEditing").click(function(){
		var parentForm =jQuery(this).parents("form"); 
		clearMarkerForm(parentForm);
		parentForm.find("#gmpEditedMarkerLocalId").val("");
        parentForm.find(".gmpEditMarkerOpts").hide();
        parentForm.find(".gmpAddMarkerOpts").show();
    })
    
    jQuery(".gmpSaveEditedMarker").click(function(){
        var markerForm   = jQuery("#gmpAddMarkerToNewForm");
        if(gmpMapEditing){
            markerForm  =   jQuery("#gmpAddMarkerToEditMap");
        }
        var markerNewParams={
            title   :   markerForm.find("#gmpNewMap_marker_title").val(),
            description    :   tinyMCE.activeEditor.getContent(),
            
            address :   markerForm.find("#gmp_marker_address").val(),
            groupId :   markerForm.find("#gmpNewMap_marker_group").val(),
            coord_x :   markerForm.find("#gmp_marker_coord_x").val(),
            coord_y :   markerForm.find("#gmp_marker_coord_y").val(),
            animation:  markerForm.find("#marker_optsanimation_text").val(),
            id      :   markerForm.find("#gmpEditedMarkerLocalId").val(),
            icon    :   gmpCurrentIcon,
        };
       updateMarker(markerNewParams);
    })
   
//       gmpDropDownObj["mapForm"]= jQuery("#gmpSelectedIcon").msDropDown({visibleRows:4});        
//       gmpDropDownObj["mapEditForm"]= jQuery("#gmpSelectedIcon_edit").msDropDown({visibleRows:4});
//       gmpDropDownObj["markerEditForm"]= jQuery("#gmpDropDownIconsSelect_MarkerEdit").msDropDown({visibleRows:4});
       
       
       jQuery("body").on("click","li.gmpAutoCompRes",function(){
           var linkElem = jQuery(this).find('a.autoCompRes');
            var latlng=linkElem.attr('id').split("__");
             if(latlng.length<2){
                return false;
            }
            var selectedAddress =linkElem.text();
            var currentForm = linkElem.parents('form');
            currentForm.find("#gmp_marker_coord_x").val(parseFloat(latlng[1]));
            currentForm.find("#gmp_marker_coord_y").val(parseFloat(latlng[0]));
            currentForm.find("#gmp_marker_address").val(selectedAddress);
            jQuery('.gmpAddressAutocomplete').hide();                
            return false;
        })  

        jQuery(document).click(function(e) { 
              if(!jQuery(e.target).is('.gmpAddressAutocomplete')){
                    if(jQuery(e.target).is(".gmp_marker_address")){
                        jQuery('.gmpAddressAutocomplete').toggle();                        
                    }else{
                      jQuery('.gmpAddressAutocomplete').hide();                  
                    }
                }
        })
      
       jQuery(".map_border_color").click(function(){
           jQuery(this).val()==""?jQuery(this).val(" "):"";
       })
       
       gmpAddMapForm =  jQuery("#gmpAddNewMapForm");
       gmpEditMapForm =  jQuery("#gmpEditMapForm");
       
       datatables.createDatatables();
})

function gmpRemoveMap(mapId){
    if(!confirm("Remove Map?")){
        return false;
    }
    if(mapId==""){
        return false;
    }
        var sendData={
            map_id:mapId,
            mod    :'gmap',
            action :'removeMap',
            reqType:'ajax'
        }
    jQuery.sendFormGmp({
	msgElID: 'gmpRemoveElemLoader__'+mapId,
        data:sendData,
        onSuccess: function(res) {
            if(!res.error){
                 setTimeout(function(){
                     jQuery(".mapsTable").find('tr#map_row_'+mapId).hide('500');
                     jQuery(".mapsTable").find('tr#map_row_'+mapId).remove();
                 },500);   
            }
        }
    })
}
var resp=""
function getMapsList(showEdit){
    jQuery("#gmpMapsListTable").remove();
    jQuery("#gmpMapsListTable_wrapper").remove();
    jQuery(".gmpMapsContainer").addClass("gmpMapsTableListLoading");
    var sendData={
            mod    :'gmap',
            action :'getMapsList',
            reqType:'ajax'
        }
    jQuery.sendFormGmp({
        data:sendData,
        onSuccess: function(res) {
            if(!res.error){
               jQuery(".gmpMapsContainer").removeClass("gmpMapsTableListLoading");                 
               jQuery(".gmpMapsContainer").append(res.html);
                datatables.reCreateTable("gmpMapsListTable")  ;             
               if(showEdit!=undefined){
                   gmpEditMap(showEdit.id)
               }
            }
        }
    })    
}
jQuery(".gmpMap_zoom").change(function(){
    var opts = {
        opt:'zoom',
        val:jQuery(this).val()
    }
    changeMap(opts);
})
jQuery(".gmpMap_type").change(function(){
    var opts = {
        opt:'type',
        val:jQuery(this).val()
    }
    changeMap(opts);
})
jQuery(".gmpMap_language").change(function(){
    var opts = {
        opt:'language',
        val:jQuery(this).val()
    }
    changeMap(opts);
})
jQuery("#map_optsenable_mouse_zoom_check").change(function(){
    if(jQuery(this).is(":checked")){
        var value=1;
    }else{
        var value=0;
    }
    var opts = {
        opt:'mouse_zoom_enable',
        val:value
    }
    changeMap(opts);
})
jQuery("#map_optsenable_zoom_check").change(function(){
    if(jQuery(this).is(":checked")){
        var value=1;
    }else{
        var value=0;
    }
    var opts = {
        opt:'zoom_enable',
        val:value
    }
    changeMap(opts);
})

function changeMap(params){
    var val =(params.val)?true:false;
    switch(params.opt){
        case "mouse_zoom_enable":
           currentMap.setOptions({
               disableDoubleClickZoom : !val,
               scrollwheel            : val
           });
        break;    
        case "zoom_enable":
           currentMap.setOptions({
               zoomControl:val 
           })
        break;
        case "type":
         currentMap.setMapTypeId(google.maps.MapTypeId[params.val]);
        break;
        case "zoom":
            currentMap.setZoom(parseInt(params.val));
        break;
    }
}
function drawAutocompleteResult(params,form){
    if(typeof(form)=='undefined'){
        form = gmpCurrentMarkerForm;
    }
    form.find(".gmpAddressAutocomplete ul").empty();
    form.find(".gmpAddressAutocomplete").slideDown();
    for(var i in params){
        var item="<li class='gmpAutoCompRes'><a class='autoCompRes' id='{position}'>{address}</a></li>";
        item = item.replace("{position}",params[i].position.lat+"__"+params[i].position.lng).replace("{address}",params[i].address);
        form.find(".gmpAddressAutocomplete ul").append(item);
    }
}


function startSearchAddress(address,form){

    if(address.length<3){
        return; 
    }      
    var sendData={
        addressStr:address,
        mod    :'marker',
        action :'findAddress',
        reqType:'ajax'
    }
    
    jQuery.sendFormGmp({
        msgElID:gmpCurrentMarkerForm.find("#gmpAddressLoader"),
        data:sendData,
        onSuccess: function(res) {
                if(res.error){
                   jQuery(".gmpFormRow.gmpAddressField .gmpAddrErrors").html(res.errors.join(","));
                   return false;
                }
           drawAutocompleteResult(res.data,form)
        }
    })    
}

    jQuery(".gmpAutocompleteArrow").click(function(){
        jQuery(this).parents("form").find(".gmpAddressAutocomplete").toggle();
    if(jQuery(this).hasClass('gmpDown')){
        jQuery(this).removeClass('gmpDown');
        jQuery(this).addClass('gmpUp');
    }else{
        jQuery(this).addClass('gmpDown');
        jQuery(this).removeClass('gmpUp');        
    }
})



function gmpCancelMapEdit(params){
        clearMarkerForm(jQuery("#gmpAddMarkerToEditMap"));
        clearMarkerForm();
        clearAddNewMapData(jQuery("#gmpEditMapForm"));
        clearAddNewMapData();
        currentMap=gmpTempMap;
        gmpMapEditing=false;
        jQuery("#gmpEditMapsContainer").empty();
        gmpCurrentMarkerForm=jQuery("#gmpAddMarkerToNewForm");
        markerArr={};    
        if(params!=undefined && !params.changeTab){
                return true;
        }
        gmpChangeTab(jQuery('.nav.nav-tabs li.gmpAddNewMap  a'));
}