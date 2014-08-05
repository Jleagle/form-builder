<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\FormBuilder\Traits\HtmlTrait;

class Field
{

  use HtmlTrait;

  private $_type;
  private $_name;
  private $_realName;
  private $_value;
  private $_layout;

  public function __constructor($name)
  {
    $this->_name = $name;
    $this->_realName = $this->_makeRealName($name);
  }

  public function setValue($value)
  {
    $this->_value= $value;
  }

  public function render()
  {

    if (!InputTypeEnum::typeExists($this->_type))
    {
      // throw exception
    }

    switch($this->_type)
    {
      case 'select':
        return $this->_renderSelect();
        break;
      case 'button':
      case 'submit':
        return $this->_renderButton();
        break;
      default:
        return $this->_renderInput();
    }
  }

  private function _renderInput()
  {
    if (!$this->_layout)
    {
      $this->_layout = '<div class="form-group">{{field}}</div>';
    }

    $this->setAttribute('value', $this->_value);
    $this->addClass('form-control');

    $field = '<input '. $this->getAttributes() .'>';
    return str_replace('{{field}}', $field, $this->_layout);
  }

  private function _makeRealName($name)
  {
    $parts = explode('.', $name);

    $return = [];
    foreach($parts as $part)
    {
      if(!empty($return))
      {
        $part = '[' . $part . ']';
      }
      $return[] = $part;
    }
    return implode('', $return);
  }

  private function _makeName($realName)
  {
    $name = str_replace('][', '.', $realName);
    $name = str_replace(']', '', $name);
    $name = str_replace('[', '.', $name);
    return $name;
  }

  public function getRealName()
  {
    return $this->_realName;
  }
}
