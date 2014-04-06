<?php

/**
 * statistics actions.
 *
 * @package    BixGame
 * @subpackage statistics
 * @author     Nikola Kotarov
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statisticsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {

    $this->totalDevices = DevicesQuery::create()->find()->count();
    $this->todayRegisteredDevicesCount = DevicesQuery::create()
                                            ->filterByCreatedAt(array('min' => time() - 24 * 60 * 60))
                                            ->find()->count();
    $this->activeDevicesLast5minsCount = ActivityQuery::create()
                                            ->filterByCreatedAt(array('min' => time() - 5 * 60))
                                            ->groupBy('Activity.DeviceId')
                                            ->find()->count();
    $this->activeDevicesLast15minsCount = ActivityQuery::create()
                                            ->filterByCreatedAt(array('min' => time() - 15 * 60))
                                            ->groupBy('Activity.DeviceId')
                                            ->find()->count();
    $this->returnedUsers = ActivityQuery::create()
                                          ->withColumn('COUNT(Activity.Id)', 'count')
                                          ->filterByEvent('STARTED')
                                          ->groupBy('Activity.DeviceId')
                                          ->having('COUNT(*) > 1', 1)
                                          ->find()->count();
    $this->returnedUsersToday = ActivityQuery::create()
                                          ->withColumn('COUNT(Activity.Id)', 'count')
                                          ->filterByCreatedAt(array('min' => time() - 24 * 60 * 60))
                                          ->filterByEvent('STARTED')
                                          ->groupBy('Activity.DeviceId')
                                          ->having('COUNT(*) > 1', 1)
                                          ->find()->count();
    $this->disabledPushNotifications = DevicesQuery::create()->filterByDeviceToken(NULL)->find()->count();
    $this->iPads = DevicesQuery::create()->filterByDeviceType("iPad")->find()->count();
    $this->iPhones = DevicesQuery::create()->filterByDeviceType("iPhone")->find()->count();
    $this->iPods = DevicesQuery::create()->filterByDeviceType("iPod touch")->find()->count();

    $this->total50SecDevices = 0;
    $this->total60SecDevices = 0;
    $this->total50SecDevicesWithMoreThan1Session = 0;
    $this->total60SecDevicesWithMoreThan1Session = 0;

    $times = $this->getSessionActivities();

    foreach($times as $data)
    {
      $time = 0;

      for ($i = (count($data) - 1); $i >= 0; $i--) {
        $sessionTime = 0;
        $index = (! (count($data[$i])) ? 0 : (count($data[$i]) - 1));
        $sessionTime = $data[$i][$index] - $data[$i][0];

        $time += $sessionTime;

        if ($sessionTime > 50 || $sessionTime > 60)
        {
          if ($sessionTime > 50)
          {
            $this->total50SecDevices++;
            if (count(data) > 1)
            {
              $this->total50SecDevicesWithMoreThan1Session++;
            }
          }
          if ($sessionTime > 60)
          {
            $this->total60SecDevices++;
            if (count(data) > 1)
            {
              $this->total60SecDevicesWithMoreThan1Session++;
            }
          }
          continue;
        }
      }
    }

    $this->paidUsersCount = ActivityQuery::create()
                                                ->filterByEvent("PAID_OK")
                                                ->groupBy('Activity.DeviceId')
                                                ->find()->count();

    $this->paidCancelUsersCount = ActivityQuery::create()
                                                ->filterByEvent("PAID_CANCEL")
//                                                ->withColumn('COUNT(Activity.Id)', 'count')
//                                                ->filterByEvent('STARTED')
                                                ->groupBy('Activity.DeviceId')
//                                                ->having('COUNT(*) > 1', 1)
                                                ->find()->count();


    $this->moreNumbersCount = ActivityQuery::create()
                                                ->filterByEvent("MORE_NUMBERS")
                                                ->groupBy('Activity.DeviceId')
                                                ->find()->count();
  }

  public function executeDevices(sfWebRequest $request)
  {
    $csv = $request->getParameter('csv');
    $this->devices = DevicesQuery::create()->find();
    $this->setupActivities = ActivityQuery::create()->filterByEvent('SETUP')->find();
    $this->times = $this->getSessionActivities();

    if ($csv)
    {
      include_once("../vendor/geoip/geoip/geoip.inc");
      include_once("../vendor/geoip/geoip/geoipcity.inc");
      $gi = geoip_open("../GeoLiteCity.dat",GEOIP_STANDARD);

      $csvHeader = "deviceID,deviceType,deviceOS,installTime,City,Country,ipAddress,Sessions,TimeInApp";
      $csvData = "";
      foreach ($this->setupActivities as $setupActivity)
      {
        $device = $setupActivity->getDevices();
        $data = $this->times[$device->getId()];
        $time = 0;

        $geoipRecord = geoip_record_by_addr($gi, $setupActivity->getIp());

        for ($i = (count($data) - 1); $i >= 0; $i--)
        {
          $sessionTime = 0;
          $index = (! (count($data[$i])) ? 0 : (count($data[$i]) - 1));
          $sessionTime = $data[$i][$index] - $data[$i][0];

          $time += $sessionTime;
        }

        $sessions = ActivityQuery::create()
          ->filterByEvent('STARTED')
          ->filterByDeviceId($device->getId())
          ->find()->count();
        $csvData .= sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
          $device->getId(), $device->getDeviceType(), $device->getDeviceOs(), $device->getCreatedAt(), $geoipRecord->city, $geoipRecord->country_name, $setupActivity->getIp(), $sessions, $time);
      }
      $this->getResponse()->setHttpHeader("Pragma", "public");
      $this->getResponse()->setHttpHeader("Expires", "0");
      $this->getResponse()->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
      $this->getResponse()->setHttpHeader("Cache-Control", "private");
      $this->getResponse()->setHttpHeader("Content-Type", "application/octet-stream");
      $this->getResponse()->setHttpHeader("Content-Disposition", "attachment; filename=\"deviceStatistics.csv\";");
      $this->getResponse()->setHttpHeader("Content-Transfer-Encoding", "binary");
      return($this->renderText("$csvHeader\n$csvData\n"));
    }
  }

  public function executeActivityDetails(sfWebRequest $request)
  {
    $deviceId = $request->getParameter("deviceId");

    $this->deviceActivities = ActivityQuery::create()->findByDeviceId($deviceId);
  }

  private function getSessionActivities()
  {
    $activities = ActivityQuery::create()
//                                     ->where('Activity.Event = ?', 'STARTED')
//                                     ->_or()
//                                     ->where('Activity.Event = ?', 'RESUMED')
//                                     ->filterByDeviceId(20)
      ->where('Activity.Event != ?', 'SETUP')
      ->where('Activity.Event != ?', 'SUSPENDED')
//                                     ->orderByDeviceId()
      ->orderByCreatedAt()
      ->find();


    $times = array();
    $currentDeviceId = 0;
    $previousDeviceId = 0;

    for ($i = 0; $i < count($activities); $i++)
    {
      $activity = $activities[$i];
      $currentDeviceId = $activity->getDeviceId();

      if (! $previousDeviceId)
        $previousDeviceId = $currentDeviceId;

      if (! isset($times[$currentDeviceId]))
      {
        $times[$currentDeviceId] = array();
      }

      $index = count($times[$currentDeviceId]);
      if ($currentDeviceId == $previousDeviceId && $index > 0 && $activity->getEvent() != "STARTED" && $activity->getEvent() != "RESUMED")
      {
        $index--;
      } else if($index == 0) {
      }

      if(! isset($times[$currentDeviceId][$index])){
        $times[$currentDeviceId][$index] = array();
      }

      $times[$currentDeviceId][$index][] = strtotime($activity->getCreatedAt());
      $previousDeviceId = $currentDeviceId;
    }

    return($times);
  }

}
