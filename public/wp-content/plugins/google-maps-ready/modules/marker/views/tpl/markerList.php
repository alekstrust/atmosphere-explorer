<div class="gmpMarkerListTable markerListConOpts tab-pane active">
    <div class='gmpMarkerListsOPerations'>
        <a class='btn btn-success' onclick="gmpRefreshMarkerList()">
            <span class='gmpIcon gmpIconRefresh'></span>
            <?php langGmp::_e("Refresh")?>
        </a>
     </div>
    <div class='gmpMTablecon'>    
        <?php
            echo @$this->tableContent;
        ?>
    </div> 
 </div>
<div class='gmpMarkerEditForm tab-pane markerListConOpts'>

        <div class='markerOptsCon'>
            <?php
            echo htmlGmp::formStart("addMarkerForm",array('attrs'=>"id='gmpEditMarkerForm'"));

            ?>
          
                <legend class='gmpFormTitle'></legend>
           <div class='gmpFormRow'>
              <?php
              $groupArr=array();
               foreach($this->marker_opts['groups'] as $item){
                   $groupArr[$item['id']]=$item['title'];
               }
                 echo htmlGmp::selectbox('marker_opts[group]',array('options'=>$groupArr,'value'=>'1' ,'attrs'=>' id="gmp_marker_group" class="gmpInputLarge gmpMarkerGroupSelect" '));
              ?>
              <label for="gmpNewMap_marker_group" class="gmpFormLabel">
                    <?php langGmp::_e('Group')?>
              </label>
           </div>  
           <div class='gmpFormRow'>
              <?php
                 echo htmlGmp::input('marker_opts[title]',array('attrs'=>" class='gmpInputLarge'  id='gmp_marker_title' required='required' "));
              ?>
              <label for="gmpNewMap_marker_title" class="gmpFormLabel">
                    <?php langGmp::_e('Marker Title')?>
              </label>
           </div>  
            
           <div class='gmpFormRow'>
              <label for="gmpNewMap_marker_desc" class="gmpFormLabel">
                    <?php langGmp::_e('Marker Description')?>
              </label>
               <br/>
               <br/>
              <?php
                 wp_editor( $content="", $id='gmpNewMap_marker_desc',
				array( 'quicktags' => false) );
              ?>

           </div>
           <div class="gmpMarkericonOptions">
                <h3><?php langGmp::_e('Marker Icon')?></h3>
                <div class="gmpFormRow">
                    <div class='gmpIconsSearchBar'>
                        <div class='lft'>
                            <a class='btn btn-default' onclick='clearIconSearch()'>
                                <?php langGmp::_e('All Icons');?>
                            </a>    
                        </div>
                        <div class='right gmpSearchFieldContainer'>
                            <div class='gmpIconSearchZoomIcon'></div><input type='text' class='gmpSearchIconField'  />
                            
                        </div> 
                   </div> 
                   <div class='gmpIconsList'>
                     <?php
                        $defIcon = false;
                     $activeClassName = '';
                        foreach($this->marker_opts['icons'] as $icon){
                           if(!$defIcon){
                               $defIcon=$icon['id'];
                               $activeClassName=' active';
                           }
                           ?>
                               <a class='markerIconItem <?php echo $activeClassName;?>' data_name='<?php echo $icon['title'];?>' data_desc="<?php echo $icon['description']; ?>" title='<?php echo $icon['title'];?>' data_val='<?php echo $icon['id'];?>'>
                                <img src="<?php echo $icon['path'];?>" class='gmpMarkerIconFile' />   
                               </a>   
                           <?php
                           $activeClassName="";
                        }
                     ?>
                   </div>  
                    <input type="hidden" name="marker_opts[icon]" value="<?php echo $defIcon;?>" id="gmpSelectedIcon" class="right">
                    <label for="newMarkerIcon"><?php langGmp::_e('Select Icon')?></label>
                </div>   

                <div class="gmpFormRow">
                   <label for=''><?php langGmp::_e('Or Upload your icon');?></label>
                    <label for="upload_image" class='right'>
                        <input id="gmpUploadIcon" class="gmpUploadIcon button" type="button" value="Upload Image" />

                    </label>

                     <div class='gmpUplRes'>
                    </div>  
                    <div class='gmpFileUpRes'>
                        <img src=''>
                    </div>   
                </div>

             </div> 
                <div class='gmpFormRow'>
                    <label for='marker_opts[animation]'><?php langGmp::_e("Marker Animation")?></label> 
                    <?php echo htmlGmp::checkboxHiddenVal("marker_opts[edit_animation]", 
                        array('attrs'=>'  class="marker_opts_animation" '));?>
                 </div>    
              <div class='gmpFormRow gmpAddressField'>
                 <label for="gmp_marker_address" class="gmpFormLabel">
                      <?php langGmp::_e('Marker Address')?>
                 </label> <span id="gmpAddressLoader"></span> <br/> 
                <small><i><?php langGmp::_e("Type address")?></i></small>
              <?php
                 echo htmlGmp::input('marker_opts[address]',
                            array('attrs'=>" class=' gmp_marker_address'  id='gmp_marker_address' "));
              ?> 
                <div class='gmpAddressAutocomplete'>
                    <ul>
                    </ul>
                </div>    
                <div class='gmpAddrErrors'></div>
              </div>     
            
            
                
                
           <div class='gmpFormRow'>
              <label for="gmpNewMap_marker_coords" class="gmpFormLabel">
                    <?php langGmp::_e('Marker Coordinates')?>
              </label>
               <br/>
               <small class="gmplft"><?php echo langGmp::_("if your don't know coordiates, leave this fields blank");?></small>
               <div class="clearfix"></div>
               <div style=''>
              <?php
                echo langGmp::_("Lat.");
                 echo htmlGmp::input('marker_opts[coord_x]',array('attrs'=>" class='gmpInputSmall'  id='gmp_marker_coord_x' "));
                 ?>
                     </div>
               <br>
                     <div>
                     <?PHP
                 echo langGmp::_("Lng.");
                 echo htmlGmp::input('marker_opts[coord_y]',array('attrs'=>" class='gmpInputSmall'  id='gmp_marker_coord_y' "));
              ?>
                  </div>

           </div>
                
         <div class='clearfix'></div>
            
         
           <div class="gmpAddMarkerOpts gmpMarkerEditformBtns">
                  <a class='btn btn-primary' id='gmpUpdateEditedMarker'>
                      <?php echo "<span  class='gmpIcon gmpIconRefresh'></span>".
                              langGmp::_("Update Marker")."<small>(".
                              langGmp::_("Preview changes").")</small>";
                      ?>
                  </a>    
               <?php

                echo htmlGmp::button( array(
                    "attrs"=>'id="gmpSaveEditedMarkerItem" type="submit" class="btn btn-success"',
                    'value'=>langGmp::_("<span class='gmpIcon gmpIconSuccess'></span>".langGmp::_("Save Changes"))));
                echo htmlGmp::button(array(
                        'attrs'=>" type='' class='btn btn-danger' onclick='return cancelEditMarkerItem()'",
                        'value'=>"<span class='gmpIcon  gmpIconReset'></span>".langGmp::_("Cancel"))) 
               ?>
                  <div id="gmpUpdateMarkerItemMsg"></div>
               </div>
          
         <?php
         echo htmlGmp::formEnd();
         ?>
         </div> 
        <div class='gmpMapForMarkerEdit' id='gmpMapForMarkerEdit'>
            
        </div>
</div>
     