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
  }

  public function executeDevices(sfWebRequest $request)
  {
    $this->devices = DevicesQuery::create()->find();
    $this->setupActivities = ActivityQuery::create()->filterByEvent('SETUP')->find();

    $activities = ActivityQuery::create()
//                                     ->where('Activity.Event = ?', 'STARTED')
//                                     ->_or()
//                                     ->where('Activity.Event = ?', 'RESUMED')
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

//      else  if ($activity->getEvent() == "STARTED")
//      {
//        $times[$currentDeviceId] = array(array());
//      } else if(!count($times[$currentDeviceId] ))
//      {
////        var_dump("dsfsdfdsf");
//        $times[$currentDeviceId] = array(array());
//      }

      $index = ((count($times[$currentDeviceId]) == 0) ? 0 : (count($times[$currentDeviceId]) - 1));

//      var_dump($currentDeviceId);
//      var_dump($index);
//      var_dump($times[$currentDeviceId]);
//      var_dump($activity->getCreatedAt());
//      var_dump($currentDeviceId);
//      var_dump($previousDeviceId);
//      echo ("----\n");
      if ($currentDeviceId != $previousDeviceId)
      {
//        $index = count($times[$currentDeviceId]);
//        $times[$currentDeviceId][$index] = array();
//        array_push($times[$currentDeviceId], array());
        $index++;
      }

      if(! isset($times[$currentDeviceId][$index])){
        $times[$currentDeviceId][$index] = array();
      }

      array_push($times[$currentDeviceId][$index], strtotime($activity->getCreatedAt()));

      $previousDeviceId = $currentDeviceId;
//      var_dump($previousDeviceId);
//      var_dump($currentDeviceId);
//      echo ("====\n");
    }

//    die();
    $this->times = $times;
  }

}
