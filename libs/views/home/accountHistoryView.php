<div class="view view-history">
	<?php include "viewHead.php"?>
	<div class="view-menu">
		<ul>
			<li class="selected">所有历史记录</li>
		</ul>
	</div>
	<div class="view-main">
		<table class="history-all" cellspacing="20">
			<tr>
				<th>时间 :</th>
				<th>行为 :</th>
			</tr>
			<?php foreach ($data as $val):?>
				<tr>
					<td><?php echo $val['log_time']?></td>
					<td><?php echo $val['log_behavior']?></td>
				</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>