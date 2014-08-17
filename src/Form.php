<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Enums\InputTypeEnum;
use Jleagle\Dom;

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
   * @var string[]
   */
  private $_attributes;

  /**
   * @var Field[]
   */
  private $_datalists;

  /**
   * @param string $action
   */
  public function __construct($action = '')
  {
    $this->setAttributes(
      [
        'action'  => $action,
        'enctype' => 'application/x-www-form-urlencoded',
        'method'  => 'post',
      ]
    );
  }


  /**
   * @param $name
   * @param array $options
   *
   * @return $this
   * @throws \Exception
   */
  private function _addDataList($name, $options)
  {
    $field = $this->getField($name);
    $fieldId = $field->getId();
    $dataListId = $fieldId.'_datalist';
    $field->setAttributes(['list' => $dataListId]);
    $dataList = new Field($name, InputTypeEnum::DATALIST);
    $dataList->setOptions($options);
    $dataList->setId($dataListId);
    $this->_datalists[$name] = $dataList;
    return $this;
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
    $this->_addField($name, InputTypeEnum::TEXT);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateField($name)
  {
    $this->_addField($name, InputTypeEnum::DATE);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateTimeField($name)
  {
    $this->_addField($name, InputTypeEnum::DATETIME);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addDateTimeLocalField($name)
  {
    $this->_addField($name, InputTypeEnum::DATETIME_LOCAL);
    return $this;
  }

  /**
   * @param string $name
   * @param array $options
   *
   * @return $this
   */
  public function addEmailField($name, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::EMAIL);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addMonthField($name)
  {
    $this->_addField($name, InputTypeEnum::MONTH);
    return $this;
  }

  /**
   * @param string $name
   * @param int    $min
   * @param int    $max
   * @param int    $step
   * @param array $options
   *
   * @return $this
   */
  public function addNumber($name, $min = null, $max = null, $step = 1, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::NUMBER, ['min' => $min, 'max' => $max, 'step' => $step]);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addPasswordField($name)
  {
    $this->_addField($name, InputTypeEnum::PASSWORD);
    return $this;
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
    $this->_addField($name, InputTypeEnum::RANGE, ['min' => $min, 'max' => $max]);
    return $this;
  }

  /**
   * @param string $name
   * @param array $options
   *
   * @return $this
   */
  public function addSearchField($name, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::SEARCH);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   * @param array $options
   *
   * @return $this
   */
  public function addTelField($name, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::TEL);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   * @param array $options
   *
   * @return $this
   */
  public function addTextField($name, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::TEXT);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addTimeField($name)
  {
    $this->_addField($name, InputTypeEnum::TIME);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addFileField($name)
  {
    $this->setAttribute('enctype', 'multipart/form-data');
    $this->_addField($name, InputTypeEnum::FILE);
    return $this;
  }

  /**
   * @param string $name
   * @param array $options
   *
   * @return $this
   */
  public function addUrlField($name, array $options = [])
  {
    $this->_addField($name, InputTypeEnum::URL);
    $this->_addDataList($name, $options);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addWeekField($name)
  {
    $this->_addField($name, InputTypeEnum::WEEK);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addButtonField($name)
  {
    $this->_addField($name, InputTypeEnum::BUTTON);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addSubmitField($name)
  {
    $this->_addField($name, InputTypeEnum::SUBMIT);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addImageField($name)
  {
    $this->_addField($name, InputTypeEnum::IMAGE);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addResetField($name)
  {
    $this->_addField($name, InputTypeEnum::RESET);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addHiddenField($name)
  {
    $this->_addField($name, InputTypeEnum::HIDDEN);
    return $this;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  public function addCheckBox($name)
  {
    $this->_addField($name, InputTypeEnum::CHECKBOX);
    return $this;
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addSelectField($name, $options)
  {
    $this->_addField($name, InputTypeEnum::SELECT, [], $options);
    return $this;
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addMultiSelectField($name, $options)
  {
    $this->_addField($name, InputTypeEnum::SELECT, ['multiple'], $options);
    return $this;
  }

  /**
   * @param string   $name
   * @param string[] $options
   *
   * @return $this
   */
  public function addRadio($name, $options)
  {
    $this->_addField($name, InputTypeEnum::RADIO, [], $options);
    return $this;
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
        try
        {
          $this->getField($k)->setValue($v);
        }catch (\Exception $e){
        }
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
   * @param string $fieldName
   *
   * @return string
   */
  public function render($fieldName = null)
  {
    // Render single field
    if ($fieldName)
    {
      try{
        $field = $this->getField($fieldName);
        return (string)$this->_renderField($field).$this->_renderDataList($fieldName);
      }catch (\Exception $e){
        return '';
      }
    }

    // Render whole form
    $children = [];
    foreach($this->_fields as $k => $field)
    {
      $children[] = $this->_renderField($field);
      $children[] = $this->_renderDataList($k);
    }
    return (string)$this->_renderForm($children);
  }

  /**
   *
   * This method only exists to override in a template class.
   *
   * @param array $children
   *
   * @return Dom
   */
  protected function _renderForm(array $children)
  {
    return new Dom(
    'form',
    $this->getAttributes(),
    $children
    );
  }

  /**
   *
   * This method only exists to override in a template class.
   *
   * @param Field $field
   *
   * @return Dom
   */
  protected function _renderField(Field $field)
  {
    return $field->render();
  }

  /**
   * @param string $field
   *
   * @return Dom
   */
  private function _renderDataList($field)
  {
    if (isset($this->_datalists[$field]))
    {
      return $this->_datalists[$field]->render();
    }
    return '';
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
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
   * @return string[]
   */
  public function getAttributes()
  {
    return $this->_attributes;
  }

  /**
   * @param $attributes
   *
   * @return $this
   */
  public function setAttributes($attributes)
  {
    $this->_attributes = $attributes;
    return $this;
  }

  /**
   * @param string $attribute
   * @param mixed $value
   *
   * @return $this
   */
  public function setAttribute($attribute, $value)
  {
    $this->_attributes[$attribute] = $value;
    return $this;
  }

}
