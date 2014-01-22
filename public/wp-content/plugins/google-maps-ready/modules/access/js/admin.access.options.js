jQuery(document).ready(function(){
	
 jQuery('#gmpAdminAccessFormIp').submit(function(){
  jQuery(this).sendFormGmp({
    msgElID: 'MSG_EL_ID_Ip',
    onSuccess: function(res) {
		if (!res.error) {
			jQuery('#gmpAdminAccessFormIp').clearForm();
			var addedElement = '<option value="' + res.data[0] +'">' + res.data[1] + '</option>';
			jQuery("select[name=selectlistGmpIp\\[\\]]").prepend(addedElement);
		}
    }
  });
  return false;
 });
 
  jQuery('#gmpAdminAccessFormUser').submit(function(){
	jQuery(this).sendFormGmp({
	  msgElID: 'MSG_EL_ID_User',
	  onSuccess: function(res) {
		 if (!res.error) {
			jQuery('#gmpAdminAccessFormUser').clearForm();
			var addedElement = '<option value="' + res.data[0] +'">' + res.data[1] + '</option>';
			jQuery("select[name=selectlistGmpUser\\[\\]]").prepend(addedElement);
		}
	  }
	});
	return false;
  });
  
  jQuery('#gmpAdminAccessFormRole').submit(function(){
	jQuery(this).sendFormGmp({
	  msgElID: 'MSG_EL_ID_Role'
	});
	return false;
  });
  
  jQuery("#delIpGmp").click(function(){
	 delElement('Ip');
  });
  
  jQuery("#delUserGmp").click(function(){
	 delElement('User');
  });
  
  function delElement(ch)
  {
	   var arrId;
		jQuery("select[name=selectlistGmp"+ch+"\\[\\]]").each(function(){
			arrId = jQuery(this).val();
		});
		  
	  if (arrId) {
		jQuery(this).sendFormGmp({
		  msgElID: 'MSG_EL_ID_'+ch,
		  data: {page: 'access', action: 'delete'+ch, reqType: 'ajax', arrElement: arrId },
		  onSuccess: function(res) {
			  if (res.data !== '') {
				res.data.forEach(function(entry) {
					jQuery("select[name=selectlistGmp"+ch+"\\[\\]] option[value="+entry+"]").remove();
				});
			  }
		  }
		});
	  }
  }
  
});

/*alert(res.errors);
alert(res.messages);*/