<?php
namespace Jleagle\FormBuilder\Traits;


trait HtmlTrait
{

  private $_attributes = [];

  /**
   * @return string
   */
  protected function getAttributes()
  {
    $return = [];
    foreach($this->_attributes as $attribute => $value)
    {
      if ($value)
      {
        $return[] = $attribute . '="' . $value . '"';
      }
      else
      {
        $return[] = $attribute;
      }
    }
    return implode(' ', $return);
  }

  /**
   * @param string $attribute
   * @param string $value
   *
   * @return $this
   */
  public function setAttribute($attribute, $value = '')
  {
    $this->_attributes[$attribute] = $value;
    return $this;

  }

  public function setAttributes(array $attributes)
  {
    foreach($attributes as $k => $v)
    {
      $this->setAttribute($k, $v);
    }
  }

  /**
   * @param string $attribute
   *
   * @return string
   */
  private function getAttribute($attribute)
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

  /**
   * @param string $class
   *
   * @return $this
   */
  public function addClass($class)
  {
    $current = $this->getAttribute('class');
    $current = explode(' ', $current);
    $current[] = $class;
    $this->setAttribute('class', implode(' ', $current));

    return $this;
  }

  /**
   * @param string $class
   *
   * @return $this
   */
  public function removeClass($class)
  {

    // Get current
    $current = $this->getAttribute('class');
    $current = explode(' ', $current);

    // Remove from array
    $new = array_diff($current, array($class));
    $this->setAttribute('class', implode(' ', $new));

    return $this;
  }

} 