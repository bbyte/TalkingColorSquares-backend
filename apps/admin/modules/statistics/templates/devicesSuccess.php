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

<table>
  <tr>
    <td>Device ID</td>
    <td>Device type</td>
    <td>Devce OS version</td>
    <td>Time</td>
    <td>City,Contry</td>
    <td>IP address</td>
    <td>Number of starts</td>
    <td>Total time in app</td>
  </tr>
  <tr><td colspan="8"><hr></td> </tr>
  <?php foreach ($setupActivities as $setupActivity): ?>
    <?php $device = $setupActivity->getDevices() ?>
    <tr>
      <td><?php echo $device->getId() ?></td>
      <td><?php echo $device->getDeviceType() ?></td>
      <td><?php echo $device->getDeviceOs() ?></td>
      <td><?php echo $device->getCreatedAt() ?></td>
      <?php $geoipRecord = geoip_record_by_addr($gi, $setupActivity->getIp()) ?>

      <td><?php echo $geoipRecord->city . ", " . $geoipRecord->country_name ?></td>
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

          $data = $times[$device->getId()]->getRawValue();

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
        <?php echo $time ?>
      </td>
    </tr>

  <?php endforeach ?>

</table>
