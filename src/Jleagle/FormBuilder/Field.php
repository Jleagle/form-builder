<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\FormBuilder\Enums\ValidatorEnum;
use Jleagle\FormBuilder\Traits\HtmlTrait;

class Field
{

  use HtmlTrait;

  private $_name;
  private $_realName;
  private $_labelName;
  private $_id;
  private $_type = 'text';
  private $_value = null;
  private $_layout = '{{label}}{{field}}';
  private $_options = [];

  // Validation
  private $_validators = [];
  private $_errors = [];

  /**
   * @param $name
   */
  public function __construct($name)
  {

    $this->_name = $name;
    $this->_realName = $this->_makeRealName($name);
    $this->_labelName = $this->_makeLabelName($name);
    $this->_id = $this->_makeId();

    $this->setAttribute('id', $this->_id);

  }

  /**
   * @return string
   */
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

  /**
   * @return string
   */
  private function _makeLabelName()
  {

    $label = str_replace('.', ' ', $this->_name);
    $label = ucwords($label);
    return $label;

  }

  /**
   * @return string
   */
  private function _makeId()
  {

    $id = str_replace('.', '-', $this->_name);
    $id = strtolower($id);
    return rand(1111, 9999).'-'.$id;

  }

  /**
   * @param $value
   *
   * @return $this
   */
  public function setValue($value)
  {

    $this->_value= $value;
    return $this;

  }

  /**
   * @param $label
   *
   * @return $this
   */
  public function setLabel($label)
  {

    $this->_labelName = $label;
    return $this;

  }

  /**
   * @param $layout
   *
   * @return $this
   */
  public function setLayout($layout)
  {

    $this->_layout = $layout;
    return $this;

  }

  /**
   * @param $type
   *
   * @return $this
   */
  public function setType($type)
  {

    $this->_type = $type;
    return $this;

  }

  /**
   * @param $options
   *
   * @return $this
   */
  public function setOptions($options)
  {

    $this->_options = $options;
    return $this;

  }

  /**
   * @return string
   */
  public function getLabel()
  {
    return $this->_labelName;
  }

  /**
   * @return string
   */
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
      case InputTypeEnum::FILE:
        return $this->_renderFile();
        break;
      case InputTypeEnum::TEXTAREA:
        return $this->_renderTextArea();
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

  /**
   * @return string
   */
  private function _renderSelect()
  {

    if (is_array($this->_value))
    {
      $this->setAttribute('multiple');
    }

    $field = '<select '. $this->getAttributes() .'>';

    $firstElement = reset($this->_options);
    if (is_array($firstElement))
    {
      foreach($this->_options as $group => $options)
      {
        $field .= '<optgroup label="'.$group.'">';
        foreach($options as $k => $v)
        {
          $selected = ((is_array($this->_value) && in_array($k, $this->_value)) || $k == $this->_value) ? ' selected' : '';
          $field .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
        }
        $field .= '</optgroup>';
      }
    }
    else
    {
      foreach($this->_options as $k => $v)
      {
        $selected = ($k == $this->_value) ? ' selected' : '';
        $field .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
      }
    }

    $field .= '</select>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    $label = $this->_renderLabel();
    $this->_layout = str_replace('{{label}}', $label, $this->_layout);

    return $this->_layout;

  }

  /**
   * @return string
   */
  private function _renderHidden()
  {

    $this->setAttribute('value', $this->_value);
    $this->setAttribute('name', $this->_realName);

    $field = '<input '. $this->getAttributes() .'>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    return $this->_layout;

  }

  /**
   * @return string
   */
  private function _renderCheckbox()
  {

    $this->setAttribute('value', $this->_value);
    $this->setAttribute('name', $this->_realName);

    // Hydrate
    if ($this->_value)
    {
      $this->setAttribute('checked');
    }

    $field = '<input '. $this->getAttributes() .'>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    $label = $this->_renderLabel();
    $this->_layout = str_replace('{{label}}', $label, $this->_layout);

    return $this->_layout;
  }

  /**
   * @return string
   */
  private function _renderRadio()
  {
    // todo
  }

  /**
   * @return string
   */
  private function _renderFile()
  {
    // todo
  }

  /**
   * @return string
   */
  private function _renderButton()
  {

    $this->setAttribute('value', $this->_value);

    $field = '<input '. $this->getAttributes() .' />';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);
    return $this->_layout;

  }

  /**
   * @return string
   */
  private function _renderText()
  {

    $this->setAttribute('value', $this->_value);
    $this->setAttribute('placeholder', $this->_labelName);
    $this->setAttribute('name', $this->_realName);

    $field = '<input '. $this->getAttributes() .'>';
    $this->_layout = str_replace('{{field}}', $field, $this->_layout);

    $label = $this->_renderLabel();
    $this->_layout = str_replace('{{label}}', $label, $this->_layout);

    return $this->_layout;

  }

  /**
   * @return string
   */
  private function _renderLabel()
  {
    return $this->renderLabelOpen().$this->renderLabelText().$this->renderLabelClose();
  }

  /**
   * @return string
   */
  private function renderLabelOpen()
  {
    return '<label for="'.$this->_id.'">';
  }

  /**
   * @return string
   */
  private function renderLabelText()
  {
    return $this->_labelName;
  }

  /**
   * @return string
   */
  private function renderLabelClose()
  {
    return '</label>';
  }

  /**
   * @param string $validatorEnum
   * @param mixed $param1
   * @param mixed $param2
   *
   * @return $this
   */
  public function addValidator($validatorEnum, $param1 = null, $param2 = null)
  {

    $params = func_get_args();
    $validator = array_shift($params);

    if(!ValidatorEnum::constantExists($validator) || !is_callable($validator))
    {
      // throw error.
    }

    $this->_validators[$validatorEnum] = [$validator, $params];
    return $this;

  }


  /**
   * @param string $validatorEnum
   *
   * @return $this
   */
  public function removeValidator($validatorEnum)
  {

    unset($this->_validators[$validatorEnum]);
    return $this;

  }

  /**
   * @param string $validator
   * @param string $alias
   *
   * @return $this
   */
  public function addCustomValidator($validator, $alias = null)
  {

    if(!is_callable($validator))
    {
      // throw error.
    }

    if ($alias)
    {
      $this->_validators[$alias] = [$validator];
    }
    else
    {
      $this->_validators[] = [$validator];
    }

    return $this;

  }

  /**
   * @param $alias
   *
   * @return $this
   */
  public function removeCustomValidator($alias)
  {

    unset($this->_validators[$alias]);
    return $this;

  }


  /**
   * @return string[]
   */
  private function validate()
  {

    foreach($this->_validators as $validator)
    {

      if (!isset($validator[1]))
      {
        $validator[1] = [];
      }

      array_unshift($validator[1], $this->_value);

      try
      {
        call_user_func_array($validator[0], $validator[1]);
      }
      catch(\Exception $e)
      {
        $this->_errors[] = $e->getMessage();
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
   * @return string[]
   */
  public function getErrors()
  {

    $this->validate();
    return $this->_errors;

  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

}
