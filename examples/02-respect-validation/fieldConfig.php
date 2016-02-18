<?php

use Respect\Validation\Validator as v;
use MooForms\Exception\ValidationException as VE;

$moods = Array('' => 'What mood are you in?', 'happy' => 'Happy', 'sad' => 'Sad', 'excited' => 'Excited');

$field_config = Array(
  Array(
    'label' => 'Name',
    'placeholder' => 'Name',
    'name' => 'name',
    'otherAttributes' => Array(
      'required alpha min-len="7"',
      'onChange' => 'console.log(this.value);',
    ),
    'validate' => function($value) {
      $value = trim($value);
      if (empty($vaulue)) throw new VE("Please enter your name.");
      if (!v::alpha()->validate($value)) throw new VE("Please only use alpha characters.");
      if (!v::length(2)->validate($value)) throw new VE("Surely your name is more than one character long!");
      return $value;
    }
    
  ),
  Array(
    'label' => 'Email',
    'type' => 'email',
    'name' => 'email',
    'placeholder' => 'Email',
    'validate' => function($email) {
      $value = trim($email);
      if (empty($vaulue)) throw new VE("Please enter your email.");
      if (!v::alpha()->validate($value)) throw new VE("Please only use alpha characters.");
      if (!v::length(2)->validate($value)) throw new VE("Surely your name is more than one character long!");
      return $value;
    }

  ),
  Array(
    'label' => 'Mood',
    'type' => 'select',
    'name' => 'mood',
    'options' => $moods,
    'onChange' => 'onMoodChange', // calls window[onChange](value, index, event)
  ),
  Array(
    'type' => 'submit',
    'value' => 'Submit',
  ),

);