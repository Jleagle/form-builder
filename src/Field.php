<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\FormBuilder\Enums\ValidatorEnum;
use Jleagle\Dom;

class Field
{

  /**
   * @var string
   */
  private $_name;

  /**
   * @var string
   */
  private $_label;

  /**
   * @var string
   */
  private $_id;

  /**
   * @var string
   */
  private $_type;

  /**
   * @var mixed
   */
  private $_value = null;

  /**
   * @var string[]|string[][]
   */
  private $_options = [];

  /**
   * @var string[]
   */
  private $_attributes = [];

  /**
   * @var array
   */

  private $_validators = [];
  /**
   * @var string[]
   */
  private $_errors = [];

  /**
   * @param string $name
   * @param string $type
   */
  public function __construct($name, $type)
  {
    $this->_type  = $type;
    $this->_name  = $this->_makeName($name);
    $this->_label = $this->_makeLabel($name);
    $this->_id    = $this->_makeId($name);
  }

  /**
   * @param $name
   *
   * @return string string
   */
  private function _makeName($name)
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

  /**
   * @param string $name
   *
   * @return mixed|string
   */
  private function _makeLabel($name)
  {
    $label = str_replace('.', ' ', $name);
    $label = ucwords($label);
    return $label;
  }

  /**
   * @param string $name
   *
   * @return string
   */
  private function _makeId($name)
  {
    $id = str_replace('.', '-', $name);
    $id = strtolower($id);
    return rand(1111, 9999).'-'.$id;
  }

  /**
   * @return Dom
   */
  public function render()
  {
    switch($this->_type)
    {
      case InputTypeEnum::SELECT:
        return $this->_renderSelect();
        break;
      case InputTypeEnum::RADIO:
        return $this->_renderRadio();
        break;
      case InputTypeEnum::CHECKBOX:
        return $this->_renderCheckbox();
        break;
      case InputTypeEnum::TEXTAREA:
        return $this->_renderTextarea();
        break;
      case InputTypeEnum::HIDDEN:
        return $this->_renderHidden();
        break;
      case InputTypeEnum::BUTTON:
      case InputTypeEnum::IMAGE:
      case InputTypeEnum::RESET:
      case InputTypeEnum::SUBMIT:
        return $this->_renderButton();
        break;
      default:
        // return new Dom('div', [], [$this->_renderDefault(), $this->_renderDataList()]);
        return $this->_renderDefault();
    }
  }

  /**
   * @return Dom
   */
  public  function renderLabel()
  {
    return new Dom('label', ['for' => $this->_id], [], $this->_label);
  }

  /**
   * @return array
   */
  private function _getDefaultAttributes()
  {
    $attributes = [];
    $attributes['id'] = $this->_id;
    $attributes['name'] = $this->_name;
    return array_merge($attributes, $this->_attributes);
  }

  /**
   * @return Dom
   */
  private function _renderDataList()
  {
    if ($this->_options)
    {
      $options = [];
      foreach($this->_options as $k => $v)
      {
        $options[] = new Dom('option', ['value' => $k, 'label' => $v]);
      }
      return new Dom('datalist', ['id' => $this->_id], $options);
    }
    return new Dom();
  }

  /**
   * @return Dom
   */
  private function _renderSelect()
  {
    $attributes = $this->_getDefaultAttributes();
    if (is_array($this->_value))
    {
      $attributes[] = 'multiple';
    }
    $firstElement = reset($this->_options);
    if (is_array($firstElement))
    {
      $optgroups = [];
      foreach($this->_options as $group => $options)
      {
        $selectOptions = $this->_renderSelectOptions($options);
        $optgroups[] = new Dom('optgroup', ['label' => $group], $selectOptions);
      }
      return new Dom('select', $attributes, $optgroups);
    }
    else
    {
      $selectOptions = $this->_renderSelectOptions($this->_options);
      return new Dom('select', $attributes, $selectOptions);
    }
  }

  /**
   * @param string[] $options
   *
   * @return Dom[]
   */
  private function _renderSelectOptions($options)
  {
    $selectOptions = [];
    foreach($options as $k => $v)
    {
      $optionAttributes = ['value' => $k];
      if ((is_array($this->_value) && in_array($k, $this->_value)) || $k == $this->_value)
      {
        $optionAttributes[] = 'selected';
      }
      $selectOptions[] = new Dom('option', $optionAttributes, [], $v);
    }
    return $selectOptions;
  }

  /**
   * @return Dom
   */
  private function _renderRadio()
  {
    // todo
    $attributes = $this->_getDefaultAttributes();

    return new Dom();
  }

  /**
   * @return Dom
   */
  private function _renderCheckbox()
  {
    $attributes = $this->_getDefaultAttributes();
    $attributes['value'] = $this->_value;
    $attributes['type'] = $this->_type;
    if ($this->_value)
    {
      $attributes[] = 'checked';
    }
    return new Dom('input', $attributes);
  }

  /**
   * @return Dom
   */
  private function _renderTextarea()
  {
    $attributes = $this->_getDefaultAttributes();
    $attributes['placeholder'] = $this->_label;
    return new Dom('textarea', $attributes, [], $this->_value);
  }

  /**
   * @return Dom
   */
  private function _renderHidden()
  {
    $attributes = $this->_getDefaultAttributes();
    $attributes['value'] = $this->_value;
    $attributes['type'] = $this->_type;
    return new Dom('input', $attributes);
  }

  /**
   * @return Dom
   */
  private function _renderButton()
  {
    $attributes = $this->_getDefaultAttributes();
    $attributes['type'] = $this->_type;
    $attributes['value'] = $this->_label;
    $attributes['placeholder'] = $this->_label;
    return new Dom('input', $attributes);
  }

  /**
   * @return Dom
   */
  private function _renderDefault()
  {
    $attributes = $this->_getDefaultAttributes();
    $attributes['type'] = $this->_type;
    $attributes['value'] = $this->_value;
    $attributes['placeholder'] = $this->_label;
    return new Dom('input', $attributes);
  }

  /**
   * @param string $validatorEnum
   * @param mixed $param1
   * @param mixed $param2
   *
   * @return $this
   * @throws \Exception
   */
  public function addValidator($validatorEnum, $param1 = null, $param2 = null)
  {
    $params = func_get_args();
    $validator = array_shift($params);
    if(!ValidatorEnum::constantExists($validator) || !is_callable($validator))
    {
      throw new \Exception('Validator not found, please use the Enum.');
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
   * @param string      $validator
   * @param string|null $alias
   *
   * @return $this
   * @throws \Exception
   */
  public function addCustomValidator($validator, $alias = null)
  {
    if(!is_callable($validator))
    {
      throw new \Exception('A custom validator must be an anonymous function.');
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
   * @param string $alias
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
    return (string)$this->render();
  }

  /**
   * @return string
   */
  public function getLabel()
  {
    return $this->_label;
  }

  /**
   * @return string
   */
  public function getType()
  {
    return $this->_type;
  }

  /**
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue($value)
  {
    $this->_value= $value;
    return $this;
  }

  /**
   * @param string $label
   *
   * @return $this
   */
  public function setLabel($label)
  {
    $this->_label = $label;
    return $this;
  }

  /**
   * @param string $type
   *
   * @return $this
   */
  public function setType($type)
  {
    $this->_type = $type;
    return $this;
  }

  /**
   * @param string[] $options
   *
   * @return $this
   */
  public function setOptions($options)
  {
    $this->_options = $options;
    return $this;
  }

  /**
   * @param string[] $attributes
   */
  public function setAttributes($attributes)
  {
    $this->_attributes = $attributes;
  }

}
