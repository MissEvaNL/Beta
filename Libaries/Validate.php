<?php
namespace Libaries;

use \App\Flash;
use \App\Models\User;

class Validate
{

    public $value;
    public $name;

    public $patterns = array(
        'uri' => '[A-Za-z0-9-\/_?&=]+',
        'url' => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha' => '[\p{L}]+',
        'alphanum' => '[\p{L}0-9]+',
        'int' => '[0-9]+',
        'float' => '[0-9\.,]+',
        'text' => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'email' => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+',
        'url' => '(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})'
    );

    public $errors = array();

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function pattern($name)
    {
        if ($name == 'array') {
            if (!is_array($this->value)) {
                $this->errors[] = Flash::addMessage($this->name . ' voldoet niet aan de eisen', 'error');
            }

        } else {

            $regex = '/^(' . $this->patterns[$name] . ')$/u';
            if ($this->value != '' && !preg_match($regex, $this->value)) {
                $this->errors[] = Flash::addMessage($this->name . ' bevat niet de juiste tekens', 'error');
            }

        }
        return $this;
    }

    public function customPattern($pattern)
    {
        $regex = '/^(' . $pattern . ')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = Flash::addMessage($this->name . ' voldoet niet aan de eisen', 'error');
        }
        return $this;
    }

    public function required()
    {
        if ((isset($this->file) && $this->file['error'] == 4) || ($this->value == '' || $this->value == null)) {
            $this->errors[] = Flash::addMessage($this->name . ' is verplicht', FLASH::WARNING);
        }
        return $this;
    }

    public function min($length)
    {

        if (is_string($this->value)) {

            if (strlen($this->value) < $length) {
                $this->errors[] = Flash::addMessage($this->name . ' heeft niet het aantal toegestaande karakters', 'error');
            }

        } else {

            if ($this->value < $length) {
                $this->errors[] = Flash::addMessage($this->name . ' heeft niet het aantal toegestaande karakters', 'error');
            }

        }
        return $this;

    }
  
    public function addError($message){
      $this->errors[] = Flash::addMessage($this->name . $message, 'error');
    }

    public function max($length)
    {

        if (is_string($this->value)) {

            if (strlen($this->value) > $length) {
                $this->errors[] = Flash::addMessage($this->name . ' heeft meer dan het toegestaande aantal karakters', 'error');
            }

        } else {

            if ($this->value > $length) {
                $this->errors[] = Flash::addMessage($this->name . ' heeft meer dan het toegestaande aantal karakters', 'error');
            }

        }
        return $this;

    }

    public function equal($value)
    {

        if ($this->value != $value) {
            $this->errors[] = Flash::addMessage($this->name . ' komt niet overeen', 'error');
        }
        return $this;

    }

    public function maxSize($size)
    {

        if ($this->file['error'] != 4 && $this->file['size'] > $size) {
            $this->errors[] =  $this->name . ' is te groot dan de toegestaande grootte: ' . number_format($size / 1048576, 2) . ' MB.';
        }
        return $this;

    }

    public function ext($extension)
    {
        if ($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension) {
            $this->errors[] = $this->name . ' bevat een ongeldige extensie ' . $extension . '.';
        }
        return $this;

    }

    public function purify($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }


    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        }
    }

    public function getErrors()
    {
      
        if (!$this->isSuccess()) {
            $return = $this->errors;
        }
        return $return;
    }


    public function displayErrors()
    {      
        $html = '<ul>';
        foreach ($this->getErrors() as $error) {
            $html .= '<li>' . $error . '</li>';
        }
        $html .= '</ul>';

        return $html;

    }

    public function result()
    {

        if (!$this->isSuccess()) {

            foreach ($this->getErrors() as $error) {
                echo "$error\n";
            }
            //exit;

        } else {
            return true;
        }

    }

    public static function is_int($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) return true;
    }

    public static function is_float($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
    }

    public static function is_alpha($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
    }

    public static function is_alphanum($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
    }

    public static function is_url($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) return true;
    }

    public static function is_uri($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }

    public static function is_bool($value)
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) return true;
    }

    public static function is_email($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
    }
}