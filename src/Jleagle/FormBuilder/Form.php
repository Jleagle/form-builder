<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Traits\HtmlTrait;

class Form
{

  use HtmlTrait;

  private $_fields = [];

  public function __constructor()
  {
    $this->setAttribute('role', 'form');
  }


  public function addField($type, $name, $default = null, $value = null)
  {
    $field = new Field();
    $field->setAttribute('type', $type);
    $field->setAttribute('name', $name);

    $this->_fields[] = $field;

    return $this;
  }

  public function addTextField($name, $default = null, $value = null)
  {
    $this->addField(Field::TEXT, $name, $default, $value);
    return $this;
  }

  public function addPasswordField($name, $default = null, $value = null)
  {
    $this->addField(Field::PASSWORD, $name, $default, $value);
    return $this;
  }

  /**
   * @return string
   */
  public function open()
  {
    return '<form ' . $this->getAttributes() . '>';
  }

  /**
   * @return string
   */
  public function close()
  {
    return '</form>';
  }

  /**
   * @return string
   */
  public function render()
  {
    $return[] = $this->open();

    foreach($this->_fields as $field)
    {
      $return[] = $field->render();
    }

    $return[] = $this->close();

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
