<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Traits\HtmlTrait;

class Form
{

  use HtmlTrait;

  private $_fields = [];

  /**
   *
   */
  public function __constructor($action = '')
  {
    $this->setAttribute('role', 'form');
    $this->setAttribute('action', $action);
  }


  /**
   * @param string $type
   * @param string $name
   * @param mixed $default
   * @param mixed $value
   *
   * @return $this
   */
  public function addField($type, $name, $default = null, $value = null)
  {
    $field = new Field($name);
    $field->setAttribute('type', $type);

    if ($value){
      $field->setValue($value);
    }else{
      $field->setValue($default);
    }


    $this->_fields[$name] = $field;

    return $this;
  }

  /**
   * @param string $name
   * @param mixed $default
   * @param mixed $value
   *
   * @return $this
   */
  public function addTextField($name, $default = null, $value = null)
  {
    $this->addField('text', $name, $default, $value);
    return $this;
  }

  /**
   * @param string $name
   * @param mixed $default
   * @param mixed $value
   *
   * @return $this
   */
  public function addPasswordField($name, $default = null, $value = null)
  {
    $this->addField('password', $name, $default, $value);
    return $this;
  }

  /**
   * @param string $name
   *
   * @throws \Exception
   */
  public function getField($name)
  {
    if (isset($this->_fields[$name]))
    {
      $this->_fields[$name];
    }

    throw new  \Exception('Field '. $name .' does not exist.');
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
   * @return string
   */
  public function render()
  {
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
