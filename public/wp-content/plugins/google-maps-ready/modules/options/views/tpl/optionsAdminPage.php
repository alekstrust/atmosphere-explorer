<link rel='stylesheet' type='text/css' href='<?php echo GMP_CSS_PATH."gmpTabsContent.css";?>' />
<link rel='stylesheet' type='text/css' href='<?php echo GMP_CSS_PATH."bootstrap.min.css";?>' />
<?php 

     wp_enqueue_script('thickbox');
    
     wp_enqueue_script('media-models');
     
     wp_enqueue_script('media-upload');
     
     wp_enqueue_media();
     if(empty($this->defaultOpenTab)){
         $this->defaultOpenTab="gmpAddNewMap";
     }
?>
<script type='text/javascript'>
        var defaultOpenTab = "<?php echo $this->defaultOpenTab;?>";
       
</script>    
<script type="text/javascript"  src="https://maps.googleapis.com/maps/api/js?&sensor=false"> </script>
<div id="gmpAdminOptionsTabs">
    <h1>
        <?php langGmp::_e('Ready! Google Maps Plugin')?>
    </h1>
	<ul class="nav nav-tabs gmpMainTab" >
		<?php foreach($this->tabsData as $tId => $tData) { 
			
		?>
		<li class="<?php echo $tId?> ">
                        <a href="#<?php echo $tId ?>">
                            <span class='gmpIcon gmpIcon<?php echo $tId ?>'></span>
                            <?php langGmp::_e($tData['title'])?>
                        </a>
                </li>
		<?php }?>
	</ul>

   
	<?php foreach($this->tabsData as $tId => $tData) { ?>
	<div id="<?php echo $tId?>" class="tab-pane" >
		<?php 

			echo $tData['content'];
 ?></div>
	<?php }?>
</div>

<div id="gmpAdminTemplatesSelection">
        <?php echo $this->presetTemplatesHtml;?>
</div>
