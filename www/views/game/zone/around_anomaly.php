<div id="content">
<div class="header">
<img src="/images/anomalies/<?=$anomaly['image']?>" alt="." />
<div class="title">�������� <?=$anomaly['name']?></div>
</div>
<table>
<tr>
<td><a class="button button12" href="/game/zone/anomaly/?side=left">������ �����</a></td>
<td><a class="button button12" href="/game/zone/anomaly/?side=right">������ ������</a></td>
</tr>
<tr>
<td>���� ������: <?=$anomaly['anom_left']?>%</td>
<td>���� ������: <?=$anomaly['anom_right']?>%</td>
</tr>
<tr>
<td>��������: <?=$anomaly['art_left']?>%</td>
<td>��������: <?=$anomaly['art_right']?>%</td>
</tr>
</table>

<div class="content_separator"></div>

