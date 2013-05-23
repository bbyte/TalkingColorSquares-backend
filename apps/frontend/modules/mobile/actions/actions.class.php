<?php

/**
 * mobile actions.
 *
 * @package    BixGame
 * @subpackage mobile
 * @author     Nikola Kotarov
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mobileActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public $jsonData = array();

  public function preSubmit(){

    // always talk as json

    $this->getResponse()->setContentType('application/json');
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('mobile', 'update');
  }

  public function executeSetup(sfWebRequest $request){

    $deviceId     = $request->getParameter('deviceId');
    $deviceType   = $request->getParameter('deviceType');
    $deviceOs     = $request->getParameter('deviceOs');
    $deviceToken  = $request->getParameter('deviceToken');

    if(! $deviceId || ! $deviceType || ! $deviceOs){

      $this->jsonData = array("message" => "Missing deviceid, device type or device model!!!");
      return($this->renderText(json_encode($this->jsonData)));
    }

    $device = DevicesQuery::filterByDeviceId($deviceId)->findOne();

    if(! $device) {
      
      $device = new Devices();
      $device->setDeviceType($deviceType);
      $device->setDeviceOs($deviceOs);
      $device->setDeviceToken($deviceToken);
      $device->save();
    }
    
    $activity = new Activity();
    $activity->setDevices($device);
    $activity->setEvent("SETUP");
    $activity->save();

    $this->jsonData = array("ok" => TRUE);

    return($this->renderText(json_encode($this->jsonData)));
  }

  public functoin executeUpdate(sfWebRequest $request){
  
    $deviceId = $request->getParameter('deviceId');
    $event    = $request->getParameter('event');

    if(! $deviceId || ! $event){

      $this->jsonData = array("message" => "Missing device id or event!!!");
      return($this->renderText(json_encode($this->jsonData)));
    }

    $device = DevicesQuery::filterByDeviceId($deviceId)->findOne();

    if(! $device){

      $this->jsonData = array("message" => "No such device: ".$deviceId." !!!");
      return($this->renderText(json_encode($this->jsonData)));
    }

    $activity = new Activity();
    $activity->setDevices($device);
    $activity->setEvent($event);
    $activity->save();

    $this->jsonData = array("ok" => TRUE);

    return($this->renderText(json_encode($this->jsonData)));
  }
}
