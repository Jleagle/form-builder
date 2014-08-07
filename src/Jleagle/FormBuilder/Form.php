<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\FormBuilder\Traits\HtmlTrait;

class Form
{

  use HtmlTrait;

  private $_fields = [];


  /**
   * @param string $action
   */
  public function __constructor($action = '')
  {
    $this->setAttribute('role', 'form');
    $this->setAttribute('action', $action);
  }

  private function _addField($name, $type, $attributes = [], $options = [])
  {

    $field = new Field($name);
    $field->setType($type);
    $field->setOptions($options);

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

        $field->setLayout('<div class="checkbox">{{label-open}}{{field}}{{label-close}}</div>');
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

        break;
      default:

        $field->setLayout('<div class="form-group">{{label}}{{field}}</div>');
        $field->setAttribute('type', $type);
        $field->addClass('form-control');

    }

    if (is_array($attributes))
    {
      foreach($attributes as $k => $v)
      {
        $field->setAttribute($k, $v);
      }
    }

    $this->_fields[$name] = $field;
    return $this;

  }


  public function addTextField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEXT);
  }

  public function addPasswordField($name)
  {
    return $this->_addField($name, InputTypeEnum::PASSWORD);
  }

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
    // todo make a mthod just for multiple?
    return $this->_addField($name, InputTypeEnum::SELECT, [], $options);
  }

  public function addRadio($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::RADIO, [], $options);
  }

  public function addRange($name, $min = null, $max = null)
  {
    return $this->_addField($name, InputTypeEnum::RANGE, ['min' => $min, 'max' => $max]);
  }

  private function fieldExists($field)
  {
    return isset($this->_fields[$field]);
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
