<form id="gmpAdminAccessFormUser">
<table>
	<tr>
		<td width="117"><?php langGmp::_e('Users')?>:</td>
		<td>
			<?php echo htmlGmp::selectlist('selectlistGmpUser', array('attrs'=>'style="width:340px;"','options' => $this->arrUser))?>
            <div align="left" class="accessDelElement"><a id="delUserGmp" href="javascript: void(0)"><?php langGmp::_e('remove User')?></a></div>
        </td>
	</tr>
	<tr>
		<td></td>
		<td>
                <?php echo htmlGmp::selectbox( 'userGmp', array('attrs' => '', 'options' => $this->selectUser) ); ?>
        </td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo htmlGmp::hidden('reqType', array('value' => 'ajax'))?>
			<?php echo htmlGmp::hidden('page', array('value' => 'access'))?> 
			<?php echo htmlGmp::hidden('action', array('value' => 'saveUser'))?>
			<?php echo htmlGmp::submit('submitUser', array('value' => langGmp::_('add User'), 'attrs' => 'class="button button-primary button-large"'))?>            
        </td>
	</tr>
</form>
</table>
