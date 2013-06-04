<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bbyte
 * Date: 6/5/13
 * Time: 12:53 AM
 * To change this template use File | Settings | File Templates.
 */
include_once("../vendor/geoip/geoip/geoip.inc");
include_once("../vendor/geoip/geoip/geoipcity.inc");
$gi = geoip_open("../GeoLiteCity.dat",GEOIP_STANDARD);
?>

<table>
  <tr>
    <td>Device type</td>
    <td>Devce OS version</td>
    <td>Time</td>
    <td>City,Contry</td>
    <td>IP address</td>
  </tr>
  <?php foreach ($setupActivities as $setupActivity): ?>
    <?php $device = $setupActivity->getDevices() ?>
    <tr>
    <td><?php echo $device->getDeviceType() ?></td>
    <td><?php echo $device->getDeviceOs() ?></td>
    <td><?php echo $device->getCreatedAt() ?></td>
    <?php $geoipRecord = geoip_record_by_addr($gi,$setupActivity->getIp()) ?>

    <td><?php echo $geoipRecord->city.", ".$geoipRecord->country_name ?></td>
    <td><?php echo $setupActivity->getIp() ?></td>
    </tr>

  <?php endforeach ?>

</table>
