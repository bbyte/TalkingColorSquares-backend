<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bbyte
 * Date: 6/7/13
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */
include_once("../vendor/geoip/geoip/geoip.inc");
include_once("../vendor/geoip/geoip/geoipcity.inc");
$gi = geoip_open("../GeoLiteCity.dat",GEOIP_STANDARD);
?>
<table width="100%">
  <tr>
    <td>Device Id</td>
    <td>Event</td>
    <td>City,Country</td>
    <td>ip</td>
    <td>Time</td>
  </tr>
  <tr>
    <td colspan="5"><hr/></td>
  </tr>
  <?php foreach ($deviceActivities as $deviceActivity): ?>
    <tr>
      <td><?php echo $deviceActivity->getDeviceId() ?></td>
      <td><?php echo $deviceActivity->getEvent() ?></td>
      <?php $geoipRecord = geoip_record_by_addr($gi, $deviceActivity->getIp()) ?>

      <td><?php echo $geoipRecord->city . ", " . $geoipRecord->country_name ?></td>
      <td><?php echo $deviceActivity->getIp() ?></td>
      <td><?php echo $deviceActivity->getCreatedAt() ?></td>
    </tr>
  <?php endforeach ?>

</table>