<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\FormBuilder\Traits\HtmlTrait;

class Form
{

  use HtmlTrait;

  /**
   * @var Field[]
   */
  private $_fields = [];

  /**
   * @var string[][]
   */
  private $_errors = [];


  /**
   * @param string $action
   */
  public function __construct($action = '')
  {
    $this->setAttribute('role', 'form');
    $this->setAttribute('action', $action);
    $this->setAttribute('method', 'post');
  }

  /**
   * @param string $name
   * @param string $type
   * @param string[] $attributes
   * @param array $options
   *
   * @return $this
   */
  private function _addField($name, $type, $attributes = [], $options = [])
  {

    $field = new Field($name);
    $field->setType($type);
    $field->setOptions($options);
    $field->setAttributes($attributes);

    if (is_array($attributes))
    {
      foreach($attributes as $k => $v)
      {
        $field->setAttribute($k, $v);
      }
    }

    switch($type)
    {
      case InputTypeEnum::SELECT:

        $field->setLayout('<div class="form-group">{{label}}{{field}}</div>');
        $field->addClass('form-control');

        break;
      case InputTypeEnum::HIDDEN:

        $field->setLayout('{{field}}');
        $field->setAttribute('type', $type);

        break;
      case InputTypeEnum::CHECKBOX:

        $field->setLayout('<div class="checkbox"><label>{{field}}{{label}}</label></div>');
        $field->addClass('checkbox');
        $field->setAttribute('type', $type);

        break;
      case InputTypeEnum::RADIO:

        $field->setLayout('<div class="radio">{{label-open}}{{field}}{{label-close}}</div>');
        $field->setAttribute('type', $type);

        break;
      case InputTypeEnum::BUTTON:
      case InputTypeEnum::SUBMIT:
      case InputTypeEnum::IMAGE:
      case InputTypeEnum::RESET:

        $field->setLayout('{{field}}');
        $field->addClass('btn btn-default');
        $field->setAttribute('type', $type);
        $field->setValue($name);

        break;
      default:

        $field->setLayout('<div class="form-group">{{label}}{{field}}</div>');
        $field->setAttribute('type', $type);
        $field->addClass('form-control');

    }

    $this->_fields[$name] = $field;
    return $this;

  }

  // Text
  public function addColourField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEXT);
  }

  public function addDateField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATE);
  }

  public function addDateTimeField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATETIME);
  }

  public function addDateTimeLocalField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATETIME_LOCAL);
  }

  public function addEmailField($name)
  {
    return $this->_addField($name, InputTypeEnum::EMAIL);
  }

  public function addMonthField($name)
  {
    return $this->_addField($name, InputTypeEnum::MONTH);
  }

  public function addNumber($name, $min = null, $max = null, $step = 1)
  {
    return $this->_addField($name, InputTypeEnum::NUMBER, ['min' => $min, 'max' => $max, 'step' => $step]);
  }

  public function addPasswordField($name)
  {
    return $this->_addField($name, InputTypeEnum::PASSWORD);
  }

  public function addRange($name, $min = null, $max = null)
  {
    return $this->_addField($name, InputTypeEnum::RANGE, ['min' => $min, 'max' => $max]);
  }

  public function addSearchField($name)
  {
    return $this->_addField($name, InputTypeEnum::SEARCH);
  }

  public function addTelField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEL);
  }

  public function addTextField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEXT);
  }

  public function addTimeField($name)
  {
    return $this->_addField($name, InputTypeEnum::TIME);
  }

  public function addUrlField($name)
  {
    return $this->_addField($name, InputTypeEnum::URL);
  }

  public function addWeekField($name)
  {
    return $this->_addField($name, InputTypeEnum::WEEK);
  }

  // Buttons
  public function addButtonField($name)
  {
    return $this->_addField($name, InputTypeEnum::BUTTON);
  }
  public function addSubmitField($name)
  {
    return $this->_addField($name, InputTypeEnum::SUBMIT);
  }
  public function addImageField($name)
  {
    return $this->_addField($name, InputTypeEnum::IMAGE);
  }
  public function addResetField($name)
  {
    return $this->_addField($name, InputTypeEnum::RESET);
  }

  // Other
  public function addHiddenField($name)
  {
    return $this->_addField($name, InputTypeEnum::HIDDEN);
  }

  public function addCheckBox($name)
  {
    return $this->_addField($name, InputTypeEnum::CHECKBOX);
  }

  public function addSelectField($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::SELECT, [], $options);
  }

  public function addMultiSelectField($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::SELECT, ['multiple'], $options);
  }

  public function addRadio($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::RADIO, [], $options);
  }

  /**
   * @param string $name
   *
   * @return Field
   *
   * @throws \Exception
   */
  public function getField($name)
  {

    if (isset($this->_fields[$name]))
    {
      return $this->_fields[$name];
    }

    throw new \Exception('Field '. $name .' does not exist.');

  }

  /**
   * @return string[]
   */
  public function getFields()
  {
    return array_keys($this->_fields);
  }

  /**
   * @param $hydration
   */
  public function hydrate($hydration)
  {

    foreach($hydration as $k => $v)
    {
      if ($this->fieldExists($k))
      {
        $this->getField($k)->setValue($v);
      }
    }

  }

  /**
   * @param $field
   *
   * @return bool
   */
  private function fieldExists($field)
  {
    return isset($this->_fields[$field]);
  }

  /**
   * @return string[][]
   */
  private function validate()
  {

    foreach($this->_fields as $field)
    {
      $errors = $field->getErrors();

      if ($errors)
      {
        $this->_errors[] = $errors;
      }
    }

    return $this->_errors;

  }

  /**
   * @return bool
   */
  public function validates()
  {

    $this->validate();
    return !(bool)$this->_errors;

  }

  /**
   * @return string[][]
   */
  public function getErrors()
  {

    $this->validate();
    return $this->_errors;

  }

  /**
   * @return string
   */
  private function _open()
  {
    return '<form ' . $this->getAttributes() . '>';
  }

  /**
   * @return string
   */
  private function _close()
  {
    return '</form>';
  }

  /**
   * @param null $field
   *
   * @return string|void
   * @throws \Exception
   */
  public function render($field = null)
  {

    if ($field)
    {
      return $this->getField($field);
    }

    $return[] = $this->_open();

    foreach($this->_fields as $field)
    {
      $return[] = $field->render();
    }

    $return[] = $this->_close();

    return implode('', $return);

  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

}
