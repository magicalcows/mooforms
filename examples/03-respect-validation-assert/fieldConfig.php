<?php

use Respect\Validation\Validator as v;

$moods = Array('' => 'What mood are you in?', 'happy' => 'Happy', 'sad' => 'Sad', 'excited' => 'Excited');

$field_config = Array(
  Array(
    'label' => 'Name',
    'placeholder' => 'Name',
    'name' => 'name',
    'otherAttributes' => Array(
      'required alpha min-len="2"',
      'onChange' => 'console.log(this.value);',
    ),
    
    'respectValidator' => function() {
      return v::alpha()->length(2);
    }
  ),
  Array(
    'label' => 'Email',
    'type' => 'email',
    'name' => 'email',
    'placeholder' => 'Email',
    'respectValidator' => function() {
      return v::email();
    }
  ),
  Array(
    'label' => 'Mood',
    'type' => 'select',
    'name' => 'mood',
    'options' => $moods,
    'respectValidator' => function() use ($moods) {
      return v::in(array_slice(array_values($moods), 1));
    },
    'onChange' => 'onMoodChange', // calls window['onChange'](value, index, event)
  ),
  Array(
    'type' => 'submit',
    'value' => 'Submit',
  ),

);