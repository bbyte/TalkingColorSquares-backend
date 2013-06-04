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
  }

}
