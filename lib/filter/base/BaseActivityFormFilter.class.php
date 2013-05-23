<?php

/**
 * Activity filter form base class.
 *
 * @package    BixGame
 * @subpackage filter
 * @author     Nikola Kotarov
 */
abstract class BaseActivityFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'device_id'  => new sfWidgetFormPropelChoice(array('model' => 'Devices', 'add_empty' => true)),
      'event'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'device_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Devices', 'column' => 'id')),
      'event'      => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Activity';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'device_id'  => 'ForeignKey',
      'event'      => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
