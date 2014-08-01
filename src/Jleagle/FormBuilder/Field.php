<?php
namespace Jleagle\FormBuilder;

use Jleagle\FormBuilder\Traits\HtmlTrait;

class Field
{

  use HtmlTrait;

  const TEXT           = 'text';
  const HIDDEN         = 'hidden';
  const PASSWORD       = 'password';
  const RADIO          = 'radio';
  const CHECKBOX       = 'checkbox';
  const MULTI_CHECKBOX = 'multi.checkbox';
  const SELECT         = 'select';
  const SELECT_MULTI   = 'select.multi';
  const TEXTAREA       = 'textarea';
  const FILE           = 'file';
  const IMAGE          = 'image';
  const BUTTON         = 'button';
  const RESET          = 'reset';
  const SUBMIT         = 'submit';
  const COLOUR         = 'color';
  const DATE           = 'date';
  const DATETIME       = 'datetime';
  const DATETIME_LOCAL = 'datetime-local';
  const EMAIL          = 'email';
  const MONTH          = 'month';
  const NUMBER         = 'number';
  const RANGE          = 'range';
  const SEARCH         = 'search';
  const TEL            = 'tel';
  const TIME           = 'time';
  const URL            = 'url';
  const WEEK           = 'week';

  private $_type;
  private $_realName = '';

  public function __constructor()
  {
    $this->_realName = '';
  }

  private function _makeRealName($options)
  {
    if (isset($options['name']))
    {
      preg_match_all('/\[(.*?)\]/', $options['name'], $matches);
      if (isset($matches[1]))
      {
        return $matches[1];
      }
    }
    return null;
  }

  public function setType($type)
  {
    $reflection = new \ReflectionClass($this);
    $constants  = $reflection->getConstants();
    if(!in_array($type, $constants))
    {
      throw new \Exception("Invalid form element type set " . $type);
    }
    $this->_type = $type;

    $this->_attributes = [];

    return $this;
  }

  public function render()
  {
    return 'x';
  }

}
