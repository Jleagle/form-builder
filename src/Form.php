<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\Helpers\Dom;

class Form
{

  /**
   * @var Field[]
   */
  private $_fields = [];

  /**
   * @var string[][]
   */
  private $_errors = [];

  /**
   * @var string
   */
  private $_action;

  /**
   * @var string
   */
  private $_enctype = 'application/x-www-form-urlencoded';

  /**
   * @var string
   */
  private $_method = 'post';

  private $_attributes = [

  ];

  /**
   * @param string $action
   */
  public function __construct($action = '')
  {
    $this->_action = $action;
  }

  /**
   * @param string   $name
   * @param string   $type
   * @param string[] $attributes
   * @param string[] $options
   *
   * @return $this
   */
  private function _addField($name, $type, $attributes = [], $options = [])
  {
    $field = new Field($name, $type);
    $field->setAttributes($attributes);
    $field->setOptions($options);
    $this->_fields[$name] = $field;
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addColourField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEXT);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATE);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateTimeField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATETIME);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateTimeLocalField($name)
  {
    return $this->_addField($name, InputTypeEnum::DATETIME_LOCAL);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addEmailField($name)
  {
    return $this->_addField($name, InputTypeEnum::EMAIL);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addMonthField($name)
  {
    return $this->_addField($name, InputTypeEnum::MONTH);
  }

  /**
   * @param string $name
   * @param int    $min
   * @param int    $max
   * @param int    $step
   *
   * @return $this
   */
  public function addNumber($name, $min = null, $max = null, $step = 1)
  {
    return $this->_addField($name, InputTypeEnum::NUMBER, ['min' => $min, 'max' => $max, 'step' => $step]);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addPasswordField($name)
  {
    return $this->_addField($name, InputTypeEnum::PASSWORD);
  }

  /**
   * @param string $name
   * @param int    $min
   * @param int    $max
   *
   * @return $this
   */
  public function addRange($name, $min = null, $max = null)
  {
    return $this->_addField($name, InputTypeEnum::RANGE, ['min' => $min, 'max' => $max]);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addSearchField($name)
  {
    return $this->_addField($name, InputTypeEnum::SEARCH);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addTelField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEL);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addTextField($name)
  {
    return $this->_addField($name, InputTypeEnum::TEXT);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addTimeField($name)
  {
    return $this->_addField($name, InputTypeEnum::TIME);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addFileField($name)
  {
    $this->_enctype = 'multipart/form-data';
    return $this->_addField($name, InputTypeEnum::FILE);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addUrlField($name)
  {
    return $this->_addField($name, InputTypeEnum::URL);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addWeekField($name)
  {
    return $this->_addField($name, InputTypeEnum::WEEK);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addButtonField($name)
  {
    return $this->_addField($name, InputTypeEnum::BUTTON);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addSubmitField($name)
  {
    return $this->_addField($name, InputTypeEnum::SUBMIT);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addImageField($name)
  {
    return $this->_addField($name, InputTypeEnum::IMAGE);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addResetField($name)
  {
    return $this->_addField($name, InputTypeEnum::RESET);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addHiddenField($name)
  {
    return $this->_addField($name, InputTypeEnum::HIDDEN);
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addCheckBox($name)
  {
    return $this->_addField($name, InputTypeEnum::CHECKBOX);
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addSelectField($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::SELECT, [], $options);
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addMultiSelectField($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::SELECT, ['multiple'], $options);
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addRadio($name, $options)
  {
    return $this->_addField($name, InputTypeEnum::RADIO, [], $options);
  }

  /**
   * @param string $name
   *
   * @return Field
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
   * @return Field[]
   */
  public function getFields()
  {
    return $this->_fields;
  }

  /**
   * @param array $hydration
   *
   * @throws \Exception
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
   * @param string $field
   *
   * @return bool
   */
  private function fieldExists($field)
  {
    return isset($this->_fields[$field]);
  }

  /**
   * @return \string[][]
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
   * @return \string[][]
   */
  public function getErrors()
  {
    $this->validate();
    return $this->_errors;
  }

  /**
   * @param string|null $field
   *
   * @return string
   * @throws \Exception
   */
  public function render($field = null)
  {
    if ($field)
    {
      $field = $this->getField($field);
      return (string)$this->renderField($field);
    }
    $children = [];
    foreach($this->_fields as $field)
    {
      $children[] = $this->renderField($field);
    }
    $return = new Dom(
      'form',
      ['action' => $this->_action, 'enctype' => $this->_enctype, 'method' => $this->_method],
      $children
    );
    return (string)$return;
  }

  /**
   *
   * This method only exists to override in a template class.
   *
   * @param Field $field
   *
   * @return Dom
   */
  protected function renderField(Field $field)
  {
    return $field->render();
  }

  /**
   *
   * This method only exists to override in a template class.
   *
   */
  protected function renderForm()
  {
    // todo
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }

  /**
   * @param $enctype
   *
   * @return $this
   */
  public function setEnctype($enctype)
  {
    $this->_enctype = $enctype;
    return $this;
  }

  /**
   * @param $method
   *
   * @return $this
   */
  public function setMethod($method)
  {
    $this->_method = $method;
    return $this;
  }

}
