var gmpAdminFormChanged = [];
window.onbeforeunload = function(){
	// If there are at lease one unsaved form - show message for confirnation for page leave
	if(gmpAdminFormChanged.length)
		return 'Some changes were not-saved. Are you sure you want to leave?';
};
jQuery(document).ready(function(){
//	jQuery('#gmpAdminOptionsTabs').tabs({
//             beforeActivate: function( event, ui ) {
//                if(typeof(gmpChangeTab)==typeof(Function)){
//                    return gmpChangeTab(event,ui) 
//                }
//             }
//        }).addClass( "ui-tabs-vertical ui-helper-clearfix" );
        
        
        
    jQuery( "#gmpAdminOptionsTabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	
	jQuery('#gmpAdminOptionsForm').submit(function(){
		jQuery(this).sendFormGmp({
			msgElID: 'gmpAdminMainOptsMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					changeModeOptionGmp( jQuery('#gmpAdminOptionsForm [name="opt_values[mode]"]').val() );
				}
			}
		});
		return false;
	});
	jQuery('#gmpAdminOptionsSaveMsg').submit(function(){
		return false;
	});
	jQuery('.gmpSetTemplateOptionButton').click(function(){
		toeShowTemplatePopupGmp();
		return false;
	});
	jQuery('.gmpGoToTemplateTabOptionButton').click(function(){
		// Go to tempalte options tab
		var index = jQuery('#gmpAdminOptionsTabs a[href="#gmpTemplateOptions"]').parents('li').index();
		jQuery('#gmpAdminOptionsTabs').tabs('option', 'active', index);
		
		toeShowTemplatePopupGmp();
		return false;
	});
	function toeShowTemplatePopupGmp() {
		var width = jQuery(document).width() * 0.9
		,	height = jQuery(document).height() * 0.9;
		tb_show(toeLangGmp('Preset Templates'), '#TB_inline?width=710&height=520&inlineId=gmpAdminTemplatesSelection', false);
		var popupWidth = jQuery('#TB_ajaxContent').width()
		,	docWidth = jQuery(document).width();
		// Here I tried to fix usual wordpress popup displace to right side
		jQuery('#TB_window').css({'left': Math.round((docWidth - popupWidth)/2)+ 'px', 'margin-left': '0'});
	}
	jQuery('#gmpAdminOptionsForm [name="opt_values[mode]"]').change(function(){
		changeModeOptionGmp( jQuery(this).val(), true );
	});
	changeModeOptionGmp( toeOptionGmp('mode') );
	selectTemplateImageGmp( toeOptionGmp('template') );
	// Remove class is to remove this class from wrapper object
	//jQuery('.gmpAdminTemplateOptRow').not('.gmpAvoidJqueryUiStyle').buttonset().removeClass('ui-buttonset');
	
	jQuery('#gmpAdminTemplateOptionsForm').submit(function(){
		jQuery(this).sendFormGmp({
			msgElID: 'gmpAdminTemplateOptionsMsg'
		});
		return false;
	});
	jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[bg_type]"]').change(function(){
		changeBgTypeOptionGmp();
	});
	changeBgTypeOptionGmp();
	
	 jQuery('.gmpOptTip').live('mouseover',function(event){
        if(!jQuery('#gmpOptDescription').attr('toeFixTip')) {
			var pageY = event.pageY - jQuery(window).scrollTop();
			var pageX = event.pageX;
			var tipMsg = jQuery(this).attr('tip');
			var moveToLeft = jQuery(this).hasClass('toeTipToLeft');	// Move message to left of the tip link
			if(typeof(tipMsg) == 'undefined' || tipMsg == '') {
				tipMsg = jQuery(this).attr('title');
			}
			toeOptShowDescriptionGmp( tipMsg, pageX, pageY, moveToLeft );
			jQuery('#gmpOptDescription').attr('toeFixTip', 1);
		}
        return false;
    });
    jQuery('.gmpOptTip').live('mouseout',function(){
		toeOptTimeoutHideDescriptionGmp();
        return false;
    });
	jQuery('#gmpOptDescription').live('mouseover',function(e){
		jQuery(this).attr('toeFixTip', 1);
		return false;
    });
	jQuery('#gmpOptDescription').live('mouseout',function(e){
		toeOptTimeoutHideDescriptionGmp();
		return false;
    });
	
	jQuery('#gmpColorBgSetDefault').click(function(){
		jQuery.sendFormGmp({
			data: {page: 'options', action: 'setTplDefault', code: 'bg_color', reqType: 'ajax'}
		,	msgElID: 'gmpAdminOptColorDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[bg_color]"]')
							.val( res.data.newOptValue )
							.css('background-color', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#gmpColorBgSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#gmpImgBgSetDefault').click(function(){
		jQuery.sendFormGmp({
			data: {page: 'options', action: 'setTplDefault', code: 'bg_image', reqType: 'ajax'}
		,	msgElID: 'gmpAdminOptImgBgDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#gmpOptBgImgPrev').attr('src', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#gmpImgBgSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#gmpImgBgRemove').click(function(){
		if(confirm(toeLangGmp('Are you sure?'))) {
			jQuery.sendFormGmp({
				data: {page: 'options', action: 'removeBgImg', reqType: 'ajax'}
			,	msgElID: 'gmpAdminOptImgBgDefaultMsg'
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#gmpOptBgImgPrev').attr('src', '');
					}
				}
			});
		}
		return false;
	});
	jQuery('#gmpLogoSetDefault').click(function(){
		jQuery.sendFormGmp({
			data: {page: 'options', action: 'setTplDefault', code: 'logo_image', reqType: 'ajax'}
		,	msgElID: 'gmpAdminOptLogoDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#gmpOptLogoImgPrev').attr('src', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#gmpLogoSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#gmpLogoRemove').click(function(){
		if(confirm(toeLangGmp('Are you sure?'))) {
			jQuery.sendFormGmp({
				data: {page: 'options', action: 'removeLogoImg', reqType: 'ajax'}
			,	msgElID: 'gmpAdminOptLogoDefaultMsg'
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#gmpOptLogoImgPrev').attr('src', '');
					}
				}
			});
		}
		return false;
	});
	jQuery('#gmpMsgTitleSetDefault').click(function(){
		jQuery.sendFormGmp({
			data: {page: 'options', action: 'setTplDefault', code: 'msg_title_params', reqType: 'ajax'}
		,	msgElID: 'gmpAdminOptMsgTitleDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						if(res.data.newOptValue.msg_title_color)
							jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[msg_title_color]"]')
								.val( res.data.newOptValue.msg_title_color )
								.css('background-color', res.data.newOptValue.msg_title_color);
						if(res.data.newOptValue.msg_title_font)
							jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[msg_title_font]"]').val(res.data.newOptValue.msg_title_font);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#gmpMsgTitleSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#gmpMsgTextSetDefault').click(function(){
		jQuery.sendFormGmp({
			data: {page: 'options', action: 'setTplDefault', code: 'msg_text_params', reqType: 'ajax'}
		,	msgElID: 'gmpAdminOptMsgTextDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						if(res.data.newOptValue.msg_text_color)
							jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[msg_text_color]"]')
								.val( res.data.newOptValue.msg_text_color )
								.css('background-color', res.data.newOptValue.msg_text_color);
						if(res.data.newOptValue.msg_text_font)
							jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[msg_text_font]"]').val(res.data.newOptValue.msg_text_font);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#gmpMsgTextSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	// If some changes was made in those forms and they were not saved - show message for confirnation before page reload
	var formsPreventLeave = ['gmpAdminOptionsForm', 'gmpAdminTemplateOptionsForm', 'gmpSubAdminOptsForm', 'gmpAdminSocOptionsForm'];
	jQuery('#'+ formsPreventLeave.join(', #')).find('input,select').change(function(){
		var formId = jQuery(this).parents('form:first').attr('id');
		changeAdminFormGmp(formId);
	});
	jQuery('#'+ formsPreventLeave.join(', #')).find('input[type=text],textarea').keyup(function(){
		var formId = jQuery(this).parents('form:first').attr('id');
		changeAdminFormGmp(formId);
	});
	jQuery('#'+ formsPreventLeave.join(', #')).submit(function(){
		if(gmpAdminFormChanged.length) {
			var id = jQuery(this).attr('id');
			for(var i in gmpAdminFormChanged) {
				if(gmpAdminFormChanged[i] == id) {
					gmpAdminFormChanged.pop(i);
				}
			}
		}
	});
	
	jQuery('.gmpAdminTemplateOptRow').find('.ui-helper-hidden-accessible').css({left: 'auto', top: 'auto'});
	
	jQuery('#toeModActivationPopupFormGmp').submit(function(){
		  jQuery(this).sendFormGmp({
			  msgElID: 'toeModActivationPopupMsgGmp',
			  onSuccess: function(res){
				  if(res && !res.error) {
					  var goto = jQuery('#toeModActivationPopupFormGmp').find('input[name=goto]').val();
					  if(goto && goto != '') {
						toeRedirect(goto);  
					  } else
						toeReload();
				  }
			  }
		  });
		  return false;
	  });
	  
	 jQuery('.toeRemovePlugActivationNoticeGmp').click(function(){
		  jQuery(this).parents('.info_box:first').animateRemove();
		  return false;
	  });
	  if(window.location && window.location.href && window.location.href.indexOf('plugins.php')) {
		  if(GMP_DATA.allCheckRegPlugs && typeof(GMP_DATA.allCheckRegPlugs) == 'object') {
			  for(var plugName in GMP_DATA.allCheckRegPlugs) {
				  var plugRow = jQuery('#'+ plugName.toLowerCase())
				  ,	updateMsgRow = plugRow.next('.plugin-update-tr');
				  if(plugRow.size() && updateMsgRow.find('.update-message').size()) {
					  updateMsgRow.find('.update-message').find('a').each(function(){
						  if(jQuery(this).html() == 'update now') {
							  jQuery(this).click(function(){
								  toeShowModuleActivationPopupGmp( plugName, 'activateUpdate', jQuery(this).attr('href') );
								  return false;
							  });
						  }
					  });
				  }
			  }
		  }
	  }
});
function toeShowModuleActivationPopupGmp(plugName, action, goto) {
	action = action ? action : 'activatePlugin';
	goto = goto ? goto : '';
	jQuery('#toeModActivationPopupFormGmp').find('input[name=plugName]').val(plugName);
	jQuery('#toeModActivationPopupFormGmp').find('input[name=action]').val(action);
	jQuery('#toeModActivationPopupFormGmp').find('input[name=goto]').val(goto);
	
	tb_show(toeLangGmp('Activate plugin'), '#TB_inline?width=710&height=220&inlineId=toeModActivationPopupShellGmp', false);
	var popupWidth = jQuery('#TB_ajaxContent').width()
	,	docWidth = jQuery(document).width();
	// Here I tried to fix usual wordpress popup displace to right side
	jQuery('#TB_window').css({'left': Math.round((docWidth - popupWidth)/2)+ 'px', 'margin-left': '0'});
}
function changeAdminFormGmp(formId) {
	if(jQuery.inArray(formId, gmpAdminFormChanged) == -1)
		gmpAdminFormChanged.push(formId);
}
function changeModeOptionGmp(option, ignoreChangePanelMode) {
	jQuery('.gmpAdminOptionRow-template, .gmpAdminOptionRow-redirect, .gmpAdminOptionRow-sub_notif_end_maint').hide();
	switch(option) {
		case 'coming_soon':
			jQuery('.gmpAdminOptionRow-template').show( GMP_DATA.animationSpeed );
			break;
		case 'redirect':
			jQuery('.gmpAdminOptionRow-redirect').show( GMP_DATA.animationSpeed );
			break;
		case 'disable':
			jQuery('.gmpAdminOptionRow-sub_notif_end_maint').show( GMP_DATA.animationSpeed );
			break;
	}
	if(!ignoreChangePanelMode) {
		// Determine should we show Comin Soon sign in wordpress admin panel or not
		if(option == 'disable' && !jQuery('#wp-admin-bar-comingsoon').hasClass('gmpHidden'))
			jQuery('#wp-admin-bar-comingsoon').addClass('gmpHidden');
		else if(option != 'disable' && jQuery('#wp-admin-bar-comingsoon').hasClass('gmpHidden'))
			jQuery('#wp-admin-bar-comingsoon').removeClass('gmpHidden');
	}
}
function setTemplateOptionGmp(code) {
	jQuery('.gmpTemplatesList .gmpTemplatePrevShell-'+ code).css('opacity', 0.5);
	jQuery.sendFormGmp({
		data: {page: 'options', action: 'save', opt_values: {template: code}, code: 'template', reqType: 'ajax'}
	,	onSuccess: function(res) {
			jQuery('.gmpTemplatesList .gmpTemplatePrevShell-'+ code).css('opacity', 1);
			if(!res.error) {
				selectTemplateImageGmp(code);
				if(res.data && res.data.new_name) {
					jQuery('.gmpAdminTemplateSelectedName').html(res.data.new_name);
				}
				if(res.data.def_options && !getCookieGmp('gmp_hide_set_defs_tpl_popup')) {
					askToSetTplDefaults(res.data.def_options);
				}
				
				// This is for style_editor module, it come with pro version.
				// I know that it's better to create events functionality, but unfortunately - I hove no time for this right now.
				if(typeof(toeGetTemplateStyleContentGmp) == 'function') {
					toeGetTemplateStyleContentGmp();
				}
			}
		}
	})
	return false;
}
function toeShowDialogCustomized(element, options) {
	options = jQuery.extend({
		resizable: false
	,	width: 500
	,	height: 300
	,	closeOnEscape: true
	,	open: function(event, ui) {
			jQuery('.ui-dialog-titlebar').css({
				'background-color': '#222222'
			,	'background-image': 'none'
			,	'border': 'none'
			,	'margin': '0'
			,	'padding': '0'
			,	'border-radius': '0'
			,	'color': '#CFCFCF'
			,	'height': '27px'
			});
			jQuery('.ui-dialog-titlebar-close').css({
				'background': 'url("../wp-includes/js/thickbox/tb-close.png") no-repeat scroll 0 0 transparent'
			,	'border': '0'
			,	'width': '15px'
			,	'height': '15px'
			,	'padding': '0'
			,	'border-radius': '0'
			,	'margin': '-7px 0 0'
			}).html('');
			jQuery('.ui-dialog').css({
				'border-radius': '3px'
			,	'background-color': '#FFFFFF'
			,	'background-image': 'none'
			,	'padding': '1px'
			,	'z-index': '300000'
			});
			jQuery('.ui-dialog-buttonpane').css({
				'background-color': '#FFFFFF'
			});
			jQuery('.ui-dialog-title').css({
				'color': '#CFCFCF'
			,	'font': '12px sans-serif'
			,	'padding': '6px 10px 0'
			});
			if(options.openCallback && typeof(options.openCallback) == 'function') {
				options.openCallback(event, ui);
			}
		}
	}, options);
	return jQuery(element).dialog(options);
}
function askToSetTplDefaults(def_options) {
	var startHtml = jQuery('#gmpAskDefaultModParams').html();
	toeShowDialogCustomized('#gmpAskDefaultModParams', {
		openCallback: function() {
			jQuery('.gmpTplDefOptionCheckShell').hide().each(function(){
				if(jQuery(this).find('input[type=checkbox]').size()) {
					var data_values = jQuery(this).find('input[type=checkbox]').val().split(',')
					,	showThisOption = false;
					for(var key in def_options) {
						for(var i in data_values) {
							if(data_values[i] == key) {
								showThisOption = true;
								break;
							}
						}
						if(showThisOption)
							break;
					}
					if(showThisOption) {
						var optName = jQuery(this).find('input[type=checkbox]').attr('name');
						if((optName == 'background_color' && (def_options.bg_type == 'color' || def_options.bg_type == 'color_image'))
							|| (optName == 'background_image' && (def_options.bg_type == 'image' || def_options.bg_type == 'color_image'))
							|| (optName != 'background_color' && optName != 'background_image')
						) {
							jQuery(this).show();
						}
					}
				}
			});
			jQuery('.gmpDefTplOptCheckbox').find('input[type=checkbox]').unbind('click').bind('click', function(){
				var parentLoaderElement = jQuery(this).parent('.gmpDefTplOptCheckbox:first')
				,	sendElement = null;
				parentLoaderElement.showLoaderGmp();
				var afterSaveAction = function() {
					if(sendElement) {
						parentLoaderElement.html( '<img src="'+ GMP_DATA.ok_icon+ '" />' );
						sendElement.unbind('cpsOptSaved', afterSaveAction);
					}
				};
				var customSuccess = false;
				switch(jQuery(this).attr('name')) {
					case 'background_color':
						sendElement = jQuery('#gmpColorBgSetDefault');
						break;
					case 'background_image':
						sendElement = jQuery('#gmpImgBgSetDefault');
						break;
					case 'logo':
						sendElement = jQuery('#gmpLogoSetDefault');
						break;
					case 'fonts':
						sendElement = jQuery('#gmpMsgTitleSetDefault, #gmpMsgTextSetDefault');
						break;
					case 'slider_images':
						customSuccess = function(data) {
							toeOptSlidesRedraw(data.slides, data.slidesNames);
						};
					default:
						jQuery.sendFormGmp({
							msgElID: parentLoaderElement
						,	data: {page: 'options', action: 'setTplDefault', reqType: 'ajax', code: jQuery(this).val().split(',')}
						,	onSuccess: function(res) {
								if(!res.error) {
									parentLoaderElement.html( '<img src="'+ GMP_DATA.ok_icon+ '" />' );
								}
								if(customSuccess && typeof(customSuccess) == 'function') {
									customSuccess(res.data);
								}
							}
						});
						break;
				}
				if(sendElement) {
					sendElement
						.unbind('cpsOptSaved', afterSaveAction)
						.bind('cpsOptSaved', afterSaveAction)
						.trigger('click');
				}
			});
		}
	,	buttons: {
			'Don\'t show this message again': function() {
				setCookieGmp('gmp_hide_set_defs_tpl_popup', true, 300);
				jQuery(this).dialog('close');
			}
		,	Close: function() {
				jQuery(this).dialog('close');
			}
		}
	,	close: function( event, ui ) {
			jQuery('#gmpAskDefaultModParams').html( startHtml );
		}
	});
}
function selectTemplateImageGmp(code) {
	jQuery('.gmpTemplatesList .gmpTemplatePrevShell-existing .button')
			.val(toeLangGmp('Apply'))
			.removeClass('gmpTplSelected');
	//jQuery('.gmpAdminTemplateShell').removeClass('gmpAdminTemplateShellSelected');
	if(code) {
		jQuery('.gmpTemplatesList .gmpTemplatePrevShell-'+ code+ ' .button')
			.val(toeLangGmp('Selected'))
			.addClass('gmpTplSelected');
		//jQuery('.gmpAdminTemplateShell-'+ code).addClass('gmpAdminTemplateShellSelected');
	}
}
function changeBgTypeOptionGmp() {
	jQuery('#gmpBgTypeStandart-selection, #gmpBgTypeColor-selection, #gmpBgTypeImage-selection').hide();
	if(jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[bg_type]"]:checked').size())
		jQuery('#'+ jQuery('#gmpAdminTemplateOptionsForm [name="opt_values[bg_type]"]:checked').attr('id')+ '-selection').show( GMP_DATA.animationSpeed );
}
/* Background image manipulation functions */
function toeOptImgCompleteSubmitNewFile(file, res) {
    toeProcessAjaxResponseGmp(res, 'gmpOptImgkMsg');
    if(!res.error) {
        toeOptImgSetImg(res.data.imgPath);
    }
}
function toeOptImgOnSubmitNewFile() {
    jQuery('#gmpOptImgkMsg').showLoaderGmp();
}
function toeOptImgSetImg(src) {
	jQuery('#gmpOptBgImgPrev').attr('src', src);
}
/* Logo image manipulation functions */
function toeOptLogoImgCompleteSubmitNewFile(file, res) {
    toeProcessAjaxResponseGmp(res, 'gmpOptLogoImgkMsg');
    if(!res.error) {
        toeOptLogoImgSetImg(res.data.imgPath);
    }
}
function toeOptLogoImgOnSubmitNewFile() {
    jQuery('#gmpOptLogoImgkMsg').showLoaderGmp();
}
function toeOptLogoImgSetImg(src) {
	jQuery('#gmpOptLogoImgPrev').attr('src', src);
}
