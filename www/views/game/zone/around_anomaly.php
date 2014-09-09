<div id="content">
<div class="header">
<img src="/images/anomalies/<?=$anomaly['image']?>" alt="." />
<div class="title">Аномалия <?=$anomaly['name']?></div>
</div>
<table>
<tr>
<td><a class="button button12" href="/game/zone/anomaly/?side=left">Обойти слева</a></td>
<td><a class="button button12" href="/game/zone/anomaly/?side=right">Обойти справа</a></td>
</tr>
<tr>
<td>Шанс увечий: <?=$anomaly['anom_left']?>%</td>
<td>Шанс увечий: <?=$anomaly['anom_right']?>%</td>
</tr>
<tr>
<td>Артефакт: <?=$anomaly['art_left']?>%</td>
<td>Артефакт: <?=$anomaly['art_right']?>%</td>
</tr>
</table>

<div class="content_separator"></div>

