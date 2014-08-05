<?php
namespace Jleagle\FormBuilder\Enums;


class InputTypeEnum
{

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

  public static function getTypes()
  {
    $reflection = new \ReflectionClass(new self());
    return $reflection->getConstants();
  }

  public static function typeExists($type)
  {
    return in_array($type, self::getTypes());
  }

}
