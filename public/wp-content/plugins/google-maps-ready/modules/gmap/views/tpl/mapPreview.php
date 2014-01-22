<link rel='stylesheet' href='<?php echo $this->getModule()->getModPath().'css/map.css'?>' type='text/css' />
<?php

if(empty($this->currentMap)){
    echo langGmp::_('Map not found');
    return;
}

 ?>
<?php
//outeGmp($this->currentMap);
    $width=$this->currentMap['html_options']['width'];
    $height=$this->currentMap['html_options']['height'];
    $classname=$this->currentMap['html_options']['classname'];
    $align = $this->currentMap['html_options']['align'];
    $mapId = "ready_google_map_".$this->currentMap['id'];
    $border = ((int)$this->currentMap['html_options']['border_width'])."px solid ".$this->currentMap['html_options']['border_color'];
   
    $ln = $this->currentMap['params']['language'];
    if($this->currentMap['params']['map_display_mode']=="popup"){
        $className="display_as_popup";
    }else{
        $className="";
    }
?>

    <style type='text/css'>
        .close_button{
            float: right;
            display: block;
            margin: -17px -13px 10px 0px;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.428571429;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;
            user-select: none;
                   color: #ffffff;
            background-color: #5bc0de;
            border-color: #46b8da;

        }
        .close_button{
            color: #ffffff;
            background-color: #39b3d7;
            border-color: #269abc;
        }
        .map_container.display_as_popup {
            display:none;
            position: absolute;
            z-index: 99999;
            border: 1px solid gray;
            border-radius: 4px;
            _position: fixed;
            padding: 5px;
            background-color: white;
            box-shadow: 0px 0px 16px 6px rgba(255, 255, 255, 0.65);
          }
        #<?php echo $mapId;?>{
            width:<?php echo $width;?>px;
            height:<?php echo $height;?>px;
            float:<?php echo $align;  ?>;
            border:<?php echo $border;?>
      
        }
        .map-preview-iumg-container img {
            width: 175px;
            cursor:pointer;
        }
        .gmp_map_opts {
            float: left;
            width: 100%;
        }
        .map_container {
            float: left;
            width: 100%;
        }
    </style>
<div class='gmp_map_opts'>
    <?php
       if($this->currentMap['params']['map_display_mode']=="popup"){
           ?>
            <div class='map-preview-iumg-container'>
                <img src='<?php echo GMP_IMG_PATH."gmap_preview.png" ?>' id="show_map_icon" title="Click to preview map">
            </div>
           <?php
        }
    ?>
    <div class='map_container <?php echo $className;?>'>
        <?php
         if($this->currentMap['params']['map_display_mode']=="popup"){
             ?>
              <a class='button  close_button' onclick="closePopup()">X</a>
             <?php
         }
        ?>
       
     <div class='gmp_MapPreview <?php echo $classname;?>' id='<?php echo $mapId ;?>'     >
    
        
    </div>
    </div>
</div>
<script type="text/javascript"  src="https://maps.googleapis.com/maps/api/js?&sensor=false&language=<?php echo $ln;?>"> </script>

<script type='text/javascript'>
    var mapForPreview=JSON.parse('<?php echo utilsGmp::listToJson($this->currentMap);?>');
    var mapId='<?php echo $mapId;?>';
</script>