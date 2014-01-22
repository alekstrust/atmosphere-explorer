var gmapObj;
var markerArr={};
var infoWindowArr=[];
function getInfoWindow(title,content){
    var text="<div class='gmpMarkerInfoWindow'>";
    text+="<div class='gmpInfoWindowtitle'>"+title;
    text+="</div>";
    text+="<div class='gmpInfoWindowContent'>"+content;
    text+="</div></div>";
     var infoWindow = new google.maps.InfoWindow({
      content: text
  });
  return infoWindow;
}
function createMarker(markerItem){
    var iconUrl =GMP_DATA.imgPath+"markers/marker_1.png"; 
    if(markerItem.icon!=undefined && markerItem.icon.path!=undefined){
        iconUrl = markerItem.icon.path;
    }
     var markerIcon = {
            url: iconUrl,
            size: new google.maps.Size(32, 37),
            origin: new google.maps.Point(0,0),
	};

            var markerLatLng = new google.maps.LatLng(markerItem.coord_y,markerItem.coord_x);
            var animType = 2;
               if(markerItem.animation==1){
                   var animType = 1;
               }

            markerArr[markerItem.id] = new google.maps.Marker({
                position: markerLatLng,
                title:markerItem.title,
                icon:markerIcon,
                draggable:false,
                map:gmapObj,
                animation:animType
           })
           
            var infoWindow=getInfoWindow(markerItem.title,markerItem.description);
                 google.maps.event.addListener(markerArr[markerItem.id], 'click', function() {
                     for(var i in infoWindowArr){
                         infoWindowArr[i].close();
                     }
                  infoWindow.open(gmapObj,markerArr[markerItem.id]);
                  toggleBounce(markerArr[markerItem.id],animType)
               });
           infoWindowArr.push(infoWindow);
             
}
function drawMarkers(markerList){
     for(var i in markerList){
         createMarker(markerList[i]);
     }   
}
function drawMap(){
       if(typeof(mapForPreview.params.map_center)!=undefined){
            var lat = mapForPreview.params.map_center.coord_y;
            var lng = mapForPreview.params.map_center.coord_x;
       } else if(mapForPreview.markers.length>0){
             var lat = mapForPreview.markers[0].coord_y;
             var lng = mapForPreview.markers[0].coord_x;
       }else{
             var lat = 44.5234475708;
             var lng = 40.1879714881 ;
       }
    var mapCenter = new google.maps.LatLng(lat,lng);

     var mapOptions = {
          center: mapCenter,
          zoom: parseInt(mapForPreview.params.zoom),
          scrollwheel: false,//mouse disable
          draggable: false,//drag map
          zoomControl: Boolean(parseInt(mapForPreview.params.enable_zoom)),
          disableDoubleClickZoom:true,
    };
    if(mapForPreview.params.enable_mouse_zoom==1){
        mapOptions.disableDoubleClickZoom=false;
        mapOptions.scrollwheel=true;
        mapOptions.draggable=true;
    }
	
        mapOptions.mapTypeId= google.maps.MapTypeId[mapForPreview.params.type];
        var map = new google.maps.Map(document.getElementById(mapId),mapOptions);
        gmapObj = map;
    if(mapForPreview.markers.length>0){
                drawMarkers(mapForPreview.markers);      
//            var bounds = new google.maps.LatLngBounds();
//            for(var i in markerArr){
//                 var mLatLng = new google.maps.LatLng(markerArr[i].position.lat(),markerArr[i].position.lng());
//         
//                  bounds.extend (mLatLng);       
//            }
//             gmapObj.fitBounds (bounds);
      }    

       google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
             gmpAddLicenzeBlock();
        });	
     
}
function closePopup(){
        jQuery(".map_container.display_as_popup").bPopup().close();    
}

jQuery(document).ready(function(){
    if(mapForPreview.params.map_display_mode=='popup'){
        jQuery("#show_map_icon").click(function(){
              jQuery(".map_container.display_as_popup").bPopup();
              drawMap();                        
        })

    }else if(mapForPreview.params.map_display_mode=='map'){
        drawMap();            
    }
})
