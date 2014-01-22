<form id="gmpAdminAccessFormRole">
<table>
  <tr>
    <td>Only users at or above this level will be able to log in:</td>
    <td>
    	<?php $selected = frameGmp::_()->getTable('access')->get('access', array('type_access' => 3)); ?>
		<?php echo htmlGmp::selectbox( 'roleGmp', array('attrs' => 'style="float:left; width:120px; margin-right:8px;"',
														'options' => $this->selectRole,
														'value'=> $selected[0]['access']) ); ?>
        <?php echo htmlGmp::hidden('reqType', array('value' => 'ajax'))?>
		<?php echo htmlGmp::hidden('page', array('value' => 'access'))?>
		<?php echo htmlGmp::hidden('action', array('value' => 'saveRole'))?>
        <?php echo htmlGmp::submit('submitRole', array('value' => langGmp::_('Save'), 'attrs' => 'class="button button-primary button-large" style="float:right;"'))?>        
    </td>
  </tr>
</table>
</form>