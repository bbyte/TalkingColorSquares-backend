<?php

/**
 * Devices form base class.
 *
 * @method Devices getObject() Returns the current form's model object
 *
 * @package    BixGame
 * @subpackage form
 * @author     Nikola Kotarov
 */
abstract class BaseDevicesForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'device_id'    => new sfWidgetFormInputText(),
      'device_token' => new sfWidgetFormInputText(),
      'device_type'  => new sfWidgetFormInputText(),
      'device_os'    => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'device_id'    => new sfValidatorString(array('max_length' => 255)),
      'device_token' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'device_type'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'device_os'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('devices[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Devices';
  }


}
