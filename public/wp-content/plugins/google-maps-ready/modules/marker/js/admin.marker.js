function gmpRemoveMarkerItem(markerId){
    var sendData ={
        mod     :   'marker',
        action  :   'removeMarker',
        reqType :   'ajax',
        id:   markerId
    }
    var respElem = jQuery("#gmpMarkerListTableLoader_"+markerId);
    jQuery.sendFormGmp({
        msgElID:"gmpMarkerListTableLoader_"+markerId,
        data:sendData,
        onSuccess:function(res){
            if(!res.error){
                setTimeout(function(){
                    jQuery("tr#markerRow_"+markerId).hide('500');
                },800);
                           
            }else{
                respElem.html(res.errors.join(","));
            }
        }
    })   
}
function gmpEditMarkerItem(markerId){
    gmpChangeTab(jQuery(".gmpMainTab li.gmpMarkerList a"));
    gmpCurrentMarkerForm = jQuery("#gmpEditMarkerForm");
    var refreshButton = gmpCurrentMarkerForm.find("a#gmpUpdateEditedMarker");
    var submitButton  = gmpCurrentMarkerForm.find("button#gmpSaveEditedMarkerItem");

     refreshButton.removeAttr("onclick");
     submitButton.removeAttr("onclick");
     refreshButton.attr("onclick","gmpDrawEditedMarker("+markerId+")");
     submitButton.attr("onclick","return gmpSaveUpdatedMarker("+markerId+")");

    jQuery(".markerListConOpts").removeClass('active');
    jQuery(".gmpMarkerEditForm").addClass('active');
    
    
    if(typeof(gmpExistsMarkers) =="undefined"){
        return false;
    }
    var currentMarker;
    var currentMarkerForm=jQuery("#gmpEditMarkerForm")
   
    for(var i in gmpExistsMarkers){
        if(gmpExistsMarkers[i].id==markerId){
            currentMarker=gmpExistsMarkers[i];
        }
    }
    gmpCurrentMarkerForm.find("legend").html("Edit Marker : "+currentMarker.title);

    var formParams={
            gmp_marker_group:currentMarker.marker_group_id,
            gmp_marker_title:currentMarker.title,
            gmp_marker_address:currentMarker.address,
            gmp_marker_desc:currentMarker.description,
            gmp_marker_coord_x:currentMarker.coord_x,
            gmp_marker_coord_y:currentMarker.coord_y,
    }
    if(currentMarker.animation=="1"){
        currentMarkerForm.find("#marker_optsedit_animation_check").attr('checked','checked');
    }else{
        currentMarkerForm.find("#marker_optsedit_animation_check").removeAttr('checked');        
    }
    currentMarkerForm.find("#marker_optsedit_animation_text").val(currentMarker.animation);    
    var iter = 0;
    //gmpCurrentMarkerForm.find()
     setcurrentIconToForm( currentMarker.icon.id,currentMarkerForm)
    jQuery("#gmpDropDownIconsSelect_MarkerEdit option").each(function(){
        if(jQuery(this).val() == currentMarker.icon.id){
           gmpDropDownObj.markerEditForm.data('dd').set('selectedIndex',iter);                
           }
        iter++;
    })
    
    
    gmpDrawMap({
        mapContainerId:"gmpMapForMarkerEdit",
        options:{
            center:{
                lat     :   currentMarker.coord_y,
                lng     :   currentMarker.coord_x
            },
            zoom        :   15
        }
    })
    drawMarker({
        icon:currentMarker.icon,
        position:{
            coord_x:currentMarker.coord_x,
            coord_y:currentMarker.coord_y,
        },
        title:currentMarker.title,
        desc:currentMarker.description,
        id:currentMarker.id,
        group_id:currentMarker.marker_group_id,
        animation:currentMarker.animation
    })
	for(var id in formParams){
        if(id=="gmp_marker_desc"){ 
            try{
		tinyMCE.editors[1].setContent(formParams[id]); 
            }catch(e){
                
            }
        }else{
           currentMarkerForm.find("#"+id).val(formParams[id]);            
        }
    }
    
}
function gmpRefreshMarkerList(){
    var sendData ={
        mod     :   'marker',
        action  :   'refreshMarkerList',
        reqType :   'ajax'
    }
    jQuery("#GmpTableMarkers").remove();
    jQuery(".gmpMTablecon").addClass("gmpMapsTableListLoading");
    jQuery.sendFormGmp({
        msgElID:"",
        data:sendData,
        onSuccess:function(res){
            if(!res.error){
                jQuery(".gmpMTablecon").removeClass("gmpMapsTableListLoading")
                jQuery(".gmpMTablecon").html(res.html);
                datatables.reCreateTable("GmpTableMarkers");
            }else{
               
            }
        }
    })     
}
function gmpGetEditMarkerFormData(form){
    if(typeof(form)=='undefined'){
        form=jQuery("#gmpEditMarkerForm");
    }
    var params={
        goup_id     :   form.find("#gmp_marker_group").val(),
        title       :   form.find("#gmp_marker_title").val(),
        address     :   form.find("#gmp_marker_address").val(),
        desc        :   tinyMCE.activeEditor.getContent(),
        position    :{
                        coord_x     :   form.find("#gmp_marker_coord_x").val(),
                        coord_y     :   form.find("#gmp_marker_coord_y").val(),            
                    },
        animation   :   form.find("input[type='hidden'].marker_opts_animation").val()
    }
    
        params.icon={
             id:form.find("#gmpSelectedIcon").val()
            }
        try{
            params.icon.path=gmpExistsIcons[params.icon.id].path;
        }catch(e){
            console.log(e);
        }
    
    return params;
}
function gmpDrawEditedMarker(markerId){
        var params = gmpGetEditMarkerFormData();
        var currentMarker = markerArr[markerId];
        updateMarker({
            icon:params.icon,
            id  :markerId,
            coord_x:params.position.coord_x,
            coord_y:params.position.coord_y,
            animation:params.animation,
            icon:params.icon.id,
            title:params.title,
            desc:params.desc,
            group_id:params.group_id
     },jQuery("#gmpEditMarkerForm"),true);    
}
function gmpSaveUpdatedMarker(markerId){
    var params = gmpGetEditMarkerFormData();
    params.id=markerId;
    var sendData={
                 mod            : "marker",
                 action         : "updateMarker",
                 reqType        : "ajax",
                 markerParams   : params
    }
    var respElem = jQuery("#gmpEditMarkerForm").find("#gmpUpdateMarkerItemMsg");
    jQuery.sendFormGmp({
        msgElID:respElem,
        data:sendData,
        onSuccess:function(res){
            if(res.error){
                respElem.html(res.errors.join(","));
            }else{
                setTimeout(function(){
                    jQuery(".markerListConOpts").toggleClass('active');
                    clearMarkerForm(jQuery("#gmpEditMarkerForm"));
                    gmpRefreshMarkerList();
                    respElem.empty();
                },800);
            }
        }
    })
    return false;
}
function cancelEditMarkerItem(){
    jQuery(".markerListConOpts").toggleClass('active');
    clearMarkerForm(jQuery("#gmpEditMarkerForm"));
    gmpRefreshMarkerList();
    return false;
}

var gmpTypeInterval;                //timer identifier
var gmpDoneTypingInterval = 5000;  //time in ms, 5 second for example

jQuery(".gmp_marker_address").keydown(function(){
        clearTimeout(gmpTypeInterval);
        
})
jQuery(".gmp_marker_address").keyup(function(){
    var addr = jQuery(this).val();
    var form;
   if(jQuery(this).parents("form").attr("id")=="gmpEditMarkerForm"){
        form = jQuery("form#gmpEditMarkerForm");
    }
     gmpTypeInterval = setTimeout(function(){
          startSearchAddress(addr,form)
      },1200);
})