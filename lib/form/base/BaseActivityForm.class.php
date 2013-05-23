<?php

/**
 * Activity form base class.
 *
 * @method Activity getObject() Returns the current form's model object
 *
 * @package    BixGame
 * @subpackage form
 * @author     Nikola Kotarov
 */
abstract class BaseActivityForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'device_id'  => new sfWidgetFormPropelChoice(array('model' => 'Devices', 'add_empty' => false)),
      'event'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'device_id'  => new sfValidatorPropelChoice(array('model' => 'Devices', 'column' => 'id')),
      'event'      => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Activity';
  }


}
