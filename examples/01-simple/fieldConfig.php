<?php
use MooForms\Exception\ValidationException;

$moods = Array('' => 'What mood are you in?', 'happy' => 'Happy', 'sad' => 'Sad', 'excited' => 'Excited');

$field_config = Array(
  Array(
    'label' => 'Name',
    'placeholder' => 'Name',
    'name' => 'name',
    'otherAttributes' => Array(
      'required alpha min-len="7"', // parsley here! =)
      'onChange' => 'console.log(this.value);', // can do key=>value too!
    ),
    'validate' => function($name) { 
      $name = trim($name); // since we return this, validators are filters too! Moo!
      if (empty($name)) throw new ValidationException("Please enter your first name.");
      if (strlen($name) < 2) throw new ValidationException("Surley your name is more than 1 character long!");
      return $name;
    }
  ),
  Array(
    'label' => 'Email',
    'type' => 'email',
    'name' => 'email',
    'placeholder' => 'Email',
    'validate' => function($email) {
      $email = trim($email);
      if (empty($email)) throw new ValidationException("Please enter your email, we promise not to share or spam it!");
      // super bad email regex: ;)
      if (!preg_match('#^[^@]+\@[^@]+\.[^@]+$#i')) throw new ValidationException("Please enter a valid email.");
      return $emaill;
      
    },
  ),
  Array(
    'label' => 'Mood',
    'type' => 'select',
    'name' => 'mood',
    'options' => $moods,
    'validate' => function($mood) use ($moods) {
      if (empty($mood) || empty($moods[$mood])) throw new ValidationException("Please choose your mood.");
      return $mood;
    },
    // MOO! Notice that we're not in "otherAttributes"! Special for select/radio/checkboxes... (this is still TODO)
    'onChange' => 'onMoodChange', // calls window['onChange'](value, index, event)
  ),
  Array(
    'type' => 'submit',
    'value' => 'Submit',
  ),

);