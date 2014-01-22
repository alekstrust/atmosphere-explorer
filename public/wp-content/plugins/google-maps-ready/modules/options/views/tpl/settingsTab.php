<div class='clearfix'></div>
<div class='gmpPluginSettingsFormContainer'>
    <h2><?php langGmp::_e("Plugin Settings");?></h2>
    <form id='gmpPluginSettingsForm'>

              <div class='gmpFormRow'>
                   <?php
                      echo htmlGmp::checkboxHiddenVal("send_statistic",
                            array("attrs"=>" class='statistic' ",
                                'checked'=>((bool)$this->saveStatistic)?"checked":""))    
                   ?>
                   <label for="gmpNewMap_title" class="gmpFormLabel">
                         <?php langGmp::_e('Send anonym statistic?')?>
                   </label>
                </div>  
       <div class='controls'>
           <?php
                echo htmlGmp::hidden("mod",array("value"=>"options"));
                echo htmlGmp::hidden("action",array("value"=>"updateStatisticStatus"));
                echo htmlGmp::hidden("reqType",array("value"=>"ajax"));
           ?>
           <div id='gmpPluginOptsMsg'></div>
           
           <input type='submit' class='btn btn-success'  value='<?php langGmp::_e("Save")?>' />
       </div>   
    </form>
</div>    
<script type='text/javascript'>
        jQuery(document).ready(function(){
            jQuery("#gmpPluginSettingsForm").submit(function(){
		jQuery("#gmpPluginSettingsForm").sendFormGmp({
			msgElID: 'gmpPluginOptsMsg'
		,	onSuccess: function(res) {
                                
                                return false;
			}
		});
                 return false;
            })
           
        })
</script>        