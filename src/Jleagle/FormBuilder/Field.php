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
  private $_labelName;
  private $_id;
  private $_value = null;
  private $_layout = '{{label}}{{field}}';
  private $_options = [];

  public function __construct($name)
  {
    $this->_name = $name;
    $this->_realName = $this->_makeRealName($name);
    $this->_labelName = $this->_makeLabelName($name);
    $this->_id = $name; // todo
  }

  private function _makeRealName()
  {
    $parts = explode('.', $this->_name);
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

  //  private function _makeName()
  //  {
  //    $name = str_replace('][', '.', $this->_name);
  //    $name = str_replace(']', '', $name);
  //    $name = str_replace('[', '.', $name);
  //    return $name;
  //  }

  private function _makeLabelName()
  {
    $label = str_replace('.', ' ', $this->_name);
    $label = ucwords($label);
    return $label;
  }

  public function setValue($value)
  {
    $this->_value= $value;
  }

  public function setLabel($label)
  {
    $this->_labelName = $label;
  }

  public function setLayout($layout)
  {
    $this->_layout = $layout;
  }

  public function setType($type)
  {
    $this->_type = $type;
  }
  public function setOptions($options)
  {
    $this->_options = $options;
  }

  public function setName($name)
  {
    $this->setAttribute('name', $name);
  }

  public function setId($id)
  {
    $this->setAttribute('id', $id);
  }

  public function render()
  {
    switch($this->_type)
    {
      case InputTypeEnum::SELECT:
        return $this->_renderSelect();
        break;
      case InputTypeEnum::HIDDEN:
        return $this->_renderHidden();
        break;
      case InputTypeEnum::CHECKBOX:
        return $this->_renderCheckbox();
        break;
      case InputTypeEnum::RADIO:
        return $this->_renderRadio();
        break;
      case InputTypeEnum::BUTTON:
      case InputTypeEnum::SUBMIT:
      case InputTypeEnum::IMAGE:
      case InputTypeEnum::RESET:
        return $this->_renderButton();
        break;
      default:
        return $this->_renderText();
    }
  }

  private function _renderSelect()
  {
    // todo hydration
    $field = '<select '. $this->getAttributes() .'>';

    $firstElement = reset($this->_options);
    if (is_array($firstElement))
    {

    }
    else
    {
      foreach($this->_options as $k => $v)
      {
        $field .= '<option value="'.$k.'">'.$v.'</option>';
      }
    }

    $field .= '</select>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    $label = $this->_renderLabel();
    $this->_layout = str_replace('{{label}}', $label, $this->_layout);

    return $this->_layout;
  }

  private function _renderHidden()
  {
    $this->setAttribute('value', $this->_value);
    $this->setName($this->_realName);

    $field = '<input '. $this->getAttributes() .'>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    return $this->_layout;
  }

  private function _renderCheckbox()
  {
  }

  private function _renderRadio()
  {
  }

  private function _renderText()
  {
    $this->setAttribute('value', $this->_value);
    $this->setAttribute('placeholder', $this->_labelName);
    $this->setName($this->_realName);

    $field = '<input '. $this->getAttributes() .'>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    $label = $this->_renderLabel();
    $this->_layout = str_replace('{{label}}', $label, $this->_layout);

    return $this->_layout;
  }

  private function _renderLabel()
  {
    return $this->renderLabelOpen().$this->renderLabelText().$this->renderLabelClose();
  }

  private function renderLabelOpen()
  {
    return '<label for="'.$this->_id.'">';
  }

  private function renderLabelText()
  {
    return $this->_labelName;
  }

  private function renderLabelClose()
  {
    return '</label>';
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

}
