<?php

/**
 * Devices filter form base class.
 *
 * @package    BixGame
 * @subpackage filter
 * @author     Nikola Kotarov
 */
abstract class BaseDevicesFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'device_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'device_token' => new sfWidgetFormFilterInput(),
      'device_type'  => new sfWidgetFormFilterInput(),
      'device_os'    => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'device_id'    => new sfValidatorPass(array('required' => false)),
      'device_token' => new sfValidatorPass(array('required' => false)),
      'device_type'  => new sfValidatorPass(array('required' => false)),
      'device_os'    => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('devices_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Devices';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'device_id'    => 'Text',
      'device_token' => 'Text',
      'device_type'  => 'Text',
      'device_os'    => 'Text',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
