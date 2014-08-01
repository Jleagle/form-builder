<?php
namespace Jleagle\FormBuilder\Traits;


trait HtmlTrait
{

  private $_attributes = [];

  public function setAttribute($attribute, $value)
  {
    $this->_attributes[$attribute] = $value;
  }

  protected function getAttributes()
  {
    $return = [];
    foreach($this->_attributes as $attribute => $value)
    {
      $return[] = $attribute . '="' . $value . '"';
    }
    return implode(' ', $return);
  }

} 