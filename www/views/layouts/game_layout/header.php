<div id="header">
<img id="logo" src="/images/logoH.png" alt="."></img>
<div class="info">
<?php
 if(isset($notification)) $this->Notification($notification);
?>
<div class="content_separator"></div>
<div class="icons">

    <span><img width="16" height="16" title="��������" src="/images/health.png" alt="."></img><?=$Player->GetParam("health")?></span>
	<?php
		$blood = $Player->GetParam("blood");
		if($blood!=0) echo '<span><img width="16" height="16" title="������������" src="/images/blood.png" alt="."></img>'.$blood.'</span>';
	
	
	?>
	<span><img width="16" height="16" title="��������" src="/images/radiation.png" alt="."></img><?=$Player->GetParam("radiation")?> ��</span>
	<span><img width="16" height="16" title="�����" src="/images/ruble.png" alt="."></img><?=$Player->GetParam("money")?></span>
	<span><img width="16" height="16" title="�������" src="/images/dollar.png" alt="."></img><?=$Player->GetParam("dollars")?></span>

</div>
</div>
</div>
<div class="content_separator"></div>