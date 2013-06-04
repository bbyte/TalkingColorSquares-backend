<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bbyte
 * Date: 6/5/13
 * Time: 12:53 AM
 * To change this template use File | Settings | File Templates.
 */?>

<table>
  <tr>
    <td>Device type</td>
    <td>Devce OS version</td>
    <td>Time</td>
    <td>IP address</td>
  </tr>
  <?php foreach ($setupActivities as $setupActivity): ?>
    <?php $device = $setupActivity->getDevices() ?>
    <tr>
    <td><?php echo $device->getDeviceType() ?></td>
    <td><?php echo $device->getDeviceOs() ?></td>
    <td><?php echo $device->getCreatedAt() ?></td>
    <td><?php echo $setupActivity->getIp() ?></td>
    </tr>

  <?php endforeach ?>

</table>
