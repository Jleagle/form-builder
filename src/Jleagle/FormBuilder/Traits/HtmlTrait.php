<?php
namespace Jleagle\FormBuilder\Traits;


trait HtmlTrait
{

  private $_attributes = [];

  protected function getAttributes()
  {
    $return = [];
    foreach($this->_attributes as $attribute => $value)
    {
      $return[] = $attribute . '="' . $value . '"';
    }
    return implode(' ', $return);
  }

  public function setAttribute($attribute, $value)
  {

    if (!$value)
    {
      unset($this->_attributes[$attribute]);
    }
    else
    {
      $this->_attributes[$attribute] = $value;
    }

    return $this;

  }

  public function getAttribute($attribute)
  {
    if (isset($this->_attributes[$attribute]))
    {
      return $this->_attributes[$attribute];
    }
    else
    {
      return '';
    }

  }

  protected function _addClass($class)
  {
    $current = $this->getAttribute('class');
    $current = explode(' ', $current);
    $current[] = $class;
    $this->setAttribute('class', implode(' ', $class));

    return $this;
  }

  protected function removeClass($class)
  {
    $current = $this->getAttribute('class');
    $current = explode(' ', $current);
    $new = array_diff($current, array($class));
    $this->setAttribute('class', implode(' ', $new));

    return $this;
  }
  

} 