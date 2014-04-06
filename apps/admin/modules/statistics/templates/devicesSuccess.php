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
//echo "<pre>";
//var_dump($times);
//echo "</pre>";
?>

<?php echo link_to("Export to CSV", "statistics/devices?csv=1") ?><br/><br/>

<table width="100%">
  <tr>
    <td>Device ID</td>
    <td>Device type</td>
    <td>Devce OS version</td>
    <td>Time</td>
    <td>City</td>
    <td>Contry</td>
    <td>IP address</td>
    <td>Sessions</td>
    <td>Time in app</td>
    <td>Actition</td>
  </tr>
  <tr><td colspan="10"><hr></td> </tr>
  <?php foreach ($setupActivities as $setupActivity): ?>
    <?php $device = $setupActivity->getDevices() ?>
    <?php $geoipRecord = geoip_record_by_addr($gi, $setupActivity->getIp()) ?>
    <tr>
      <td><?php echo $device->getId() ?></td>
      <td><?php echo $device->getDeviceType() ?></td>
      <td><?php echo $device->getDeviceOs() ?></td>
      <td><?php echo $device->getCreatedAt() ?></td>
      <td><?php echo $geoipRecord->city ?></td>
      <td><?php echo $geoipRecord->country_name ?></td>
      <td><?php echo $setupActivity->getIp() ?></td>
      <td>
        <?php echo ActivityQuery::create()
//        ->withColumn('COUNT(Activity.Id)', 'count')
          ->filterByEvent('STARTED')
          ->filterByDeviceId($device->getId())
//        ->groupBy('Activity.DeviceId')
//        ->having('COUNT(*) > 1', 1)
          ->find()->count()?>
      </td>
      <td>
        <?php
          $time = 0;
          if (! $times[$device->getId()]) continue;
          $data = $times[$device->getId()]->getRawValue();

//        var_dump($data);

          for ($i = (count($data) - 1); $i >= 0; $i--) {
            $sessionTime = 0;
            $index = (! (count($data[$i])) ? 0 : (count($data[$i]) - 1));
            if($index == 0) {
//              var_dump($data);
            }
//            var_dump($index);
            $sessionTime = $data[$i][$index] - $data[$i][0];

            $time += $sessionTime;
          }

        ?>
        <?php echo gmdate("i \m\i\\n\s s \s\e\c\s", $time) ?>
      </td>
      <td>
        <?php echo link_to("Full log", "statistics/activityDetails?deviceId=".$device->getId()) ?>
      </td>
    </tr>

  <?php endforeach ?>

</table>
