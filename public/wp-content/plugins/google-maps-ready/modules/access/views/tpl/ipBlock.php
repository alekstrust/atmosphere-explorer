<form id="gmpAdminAccessFormIp">
<table>
	<tr>
		<td width="117"><?php langGmp::_e('IP Address')?>:</td>
		<td>
			<?php echo htmlGmp::selectlist('selectlistGmpIp', array('attrs'=>'style="width:340px;"','options' => $this->arrIp))?>
            <div align="left" class="accessDelElement"><a id="delIpGmp" href="javascript: void(0)"><?php langGmp::_e('remove IP Address')?></a></div>
        </td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo htmlGmp::text('ipAddressGmp', array('value' => ''))?></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo htmlGmp::hidden('reqType', array('value' => 'ajax'))?>
			<?php echo htmlGmp::hidden('page', array('value' => 'access'))?> <!--page = для адинки | mod = для сайт-->
			<?php echo htmlGmp::hidden('action', array('value' => 'saveIp'))?> <!--метод-->
			<?php echo htmlGmp::submit('submitIp', array('value' => langGmp::_('add Ip address'), 'attrs' => 'class="button button-primary button-large"'))?>            
        </td>
	</tr>
</table>
</form>
