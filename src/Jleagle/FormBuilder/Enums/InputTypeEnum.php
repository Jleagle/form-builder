<?php
namespace Jleagle\FormBuilder\Enums;


class InputTypeEnum
{

  const BUTTON         = 'button';
  const CHECKBOX       = 'checkbox';
  const COLOUR         = 'color';
  const DATE           = 'date';
  const DATETIME       = 'datetime';
  const DATETIME_LOCAL = 'datetime-local';
  const EMAIL          = 'email';
  const FILE           = 'file';
  const HIDDEN         = 'hidden';
  const IMAGE          = 'image';
  const MONTH          = 'month';
  const NUMBER         = 'number';
  const PASSWORD       = 'password';
  const RADIO          = 'radio';
  const RANGE          = 'range';
  const RESET          = 'reset';
  const SEARCH         = 'search';
  const SELECT         = 'select';
  const SUBMIT         = 'submit';
  const TEL            = 'tel';
  const TEXT           = 'text';
  const TEXTAREA       = 'textarea';
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
