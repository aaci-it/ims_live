<?php $this->load->view('header');?>
<div id="body">
<?php echo form_open();
$tokens = explode('/', current_url());
$get = $tokens[sizeof($tokens)-1];
$get2 = $tokens[sizeof($tokens)-2];?>
<table>
	<tr>
		<td>
			<table>
			<?php foreach($bplist as $r):?>
			<tr>
				<td><?php echo anchor('main/businesspartner/item/'.$r->CardCode,$r->CardName);?></td>
			</tr>
			<?php endforeach;?>
			</table>
		</td>
		<td>
			<table>
				<?php if(isset($itemlist)): foreach ($itemlist as $r1):?>
				<tr>
					<td><?php echo anchor('main/businesspartner/warehouse/'.$r1->comm__id.'/'.$get,$r1->comm__name);?></td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
			</table>
		</td>
		<td>
			<table>
				<?php if(isset($whlist)): foreach($whlist as $r):?>
				<?php if ($r->sqty == null){
					$sqty = 0;}
				else{
					$sqty = $r->sqty;}
				if ($r->tqty == null){
					$tqty = 0;}
				else{
					$tqty = $r->tqty;}
				if ($r->rqty == null){
					$rqty = 0;}
				else{
					$rqty = $r->rqty;}
				$qty = ($sqty - ($tqty + $rqty));?>
				<tr>
					<td><?php echo anchor('main/wh_delivery/get/'.$get2.'/out/'.$r->wh_code,$r->wh_name.'-'.$qty);?></td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
			</table>
		</td>
	</tr>
</table>
<?php echo form_close();?>
</div>
<?php $this->load->view('footer');?>