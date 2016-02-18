<?php
use MooForms\Exception\ValidationException;

$moods = Array('' => 'What mood are you in?', 'happy' => 'Happy', 'sad' => 'Sad', 'excited' => 'Excited');

$colors = Array('Red', 'Blue','Green','Pink','Yellow','Other');

$foods = Array('Steak', 'Chicken', 'Pizza', 'Waffles', 'Beef', 'Mozzarella cheese', 'Bacon', 'Corned beef', 'Avocado', 'Pasta', 'Pineapple', 
  'Peanut Butter', 'Hamburgers', 'Sushi', 'Pancakes', 'Noodles', 'Chocolate', 'Blueberries', 'Salmon', 'Banana', 'Ice Cream', 'Ham', 'Oysters', 
  'Mashed potatoes', 'Soup', 'Asparagus', 'Sweet potato', 'Donuts', 'Turkey', 'Candy', 'Grapes', 'Popcorn', 'Cashew nuts', 'Eggs', 'Watermelon', 
  'Tuna', 'Cheddar cheese', 'Prawns', 'Strawberries', 'Artichokes', 'Asparagus', 'Halibut', 'Almonds', 'Mango', 'Meatballs', 'Apples', 'Lamb', 
  'Sweetcorn', 'Mushrooms', 'Rice pudding');

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
        if (!preg_match('#^[^@]+\@[^@]+\.[^@]+$#i', $email)) throw new ValidationException("Please enter a valid email.");
        return $email;
        
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
      // MOO! Notice that we're not in "otherAttributes"! Special for select/radio/checkboxes...
      'onChange' => 'onMoodChange', // calls window['onChange'](value, index, event)
    ),
    Array(
      'label' => 'Choose your Favorite Foods',
      'type' => 'select',
      'name' => 'food',
      'multiple' => true,
      'size' => 10,
      'help' => '(select multiple: ctrl+click on PC / cmd+click on Mac)',
      'options' => $foods,
      'validate' => function($food) use ($foods) {
        if (empty($food) || empty($foods[$food])) throw new ValidationException("Please choose your mood.");
        return $food;
      },
      // MOO! Notice that we're not in "otherAttributes"! Special for select/radio/checkboxes...
      'onChange' => 'onMoodChange', // calls window['onChange'](value, index, event)
    ),
    
    Array(
        'label' => 'Favorite Color',
        'type' => 'radios',
        'name' => 'fav_color',
        'options' => $colors,
        'validate' => function($value) use ($colors)  {
            $value = trim($value);
            if (empty($value)) throw new ValidationException("Please choose a color.");
            if (!in_array($value, $colors)) throw new ValidationException("Please chooe a color.");
            return $value;
        }
    ),
    
    Array(
        'label' => 'Other Color',
        'type' => 'text',
        'name' => 'other_color',
        'validate' => function($value, $form_data){
            $value = trim($value);
            if (@$form_data['fav_color'] === 'Other') {
                if (empty($value)) throw new ValidationException("Please enter your favorite color.");
            }
            return $value;
        }
    ),
    Array(
        'label' => 'Favorite Colors',
        'type' => 'checkboxes',
        'name' => 'fav_colors',
        'help' => '(choose at least 3)',
        'options' => $colors,
        'validate' => function($value) use ($colors)  {
            if (empty($value)) throw new ValidationException("Please choose a color.");
            if (!is_array($value)) throw new ValidationException("Invalid Favorite Colors.");
            if (count($value) < 3) throw new ValidationException("Please choose at least 3 colors.");
            foreach($value as $color) {
                if (!in_array($color, $colors)) throw new ValidationException("Invalid Color: $color");
            }
            return $value;
        }
    ),
    Array(
        'label' => 'Inline  Colors',
        'inline' => true,
        'type' => 'checkboxes',
        'name' => 'inline_colors',
        'help' => '(choose at least 3)',
        'options' => $colors,
        'validate' => function($value) use ($colors)  {
            if (empty($value)) throw new ValidationException("Please choose a color.");
            if (!is_array($value)) throw new ValidationException("Invalid Favorite Colors.");
            if (count($value) < 3) throw new ValidationException("Please choose at least 3 colors.");
            foreach($value as $color) {
                if (!in_array($color, $colors)) throw new ValidationException("Invalid Color: $color");
            }
            return $value;
        }
    ),
    Array(
        'label' => 'Comments',
        'type' => 'textarea',
        'name' => 'comments'
    ),
    

    // Begin non-standard form controls 
    Array(
        'label' => 'Transfer Amount',
        'type' => 'text',
        'placeholder' => 'Amount',
        'addonPre' => '$',
        'addonPost' => '.00',
        'name' => 'transfer',
        'validate' => function($value) {
            $value = trim($value);
            if (empty($value)) throw new ValidationException("Please enter an amount to transfer.");
            if (!preg_match("#^(\d+|\d{1,3}(,\d{3})*)(\.\d+)?$#", $value)) throw new ValidationException("Pleae enter a valid number.");
            return $value;
        }
    ),
    /*
    Array(
        'label' => '',
        'type' => '',
        'name' => '',
        'options' => Array(),
        'validate' => function($value) {
            $value = trim($value);
            if (empty($value)) throw new ValidationException("Please enter ");
            return $value;
        }
    ),
    */
    Array(
        'label' => 'Agree to Terms',
        'type' => 'checkbox',
        'name' => 'terms',
        'opt_value' => 1,
        'validate' => function($value) {
            $value = trim($value);
            if (empty($value)) throw new ValidationException("You must agree to the terms.");
            return $value;
        }
    ),
    

    Array(
      'type' => 'html',
      'htmlOpen' => '<div class=well><h3>Custom HTML!</h3></div>'
      ),
    Array(
      'type' => 'html',
      'htmlOpen' => '<div class=row>',
      'htmlClose' => '</div>',
      'children' => Array(
        Array(
          'type' => 'html', 
          'htmlOpen' => '<div class="col col-sm-4">',
          'htmlClose'=>'</div>',
          'children'=>Array(
            Array(
              'label' => 'Val A',
              'hideLabel' => true,
              'placeholder' => 'Val A',
              'name' => 'vala',
              'validate' => function($value) {
                $value = trim($value);
                if (!is_numeric($value)) throw new ValidationException("Please enter a numeric value.");
              }
            ),
        )),
        Array(
          'type' => 'html', 
          'htmlOpen' => '<div class="col col-sm-4">',
          'htmlClose'=>'</div>',
          'children'=>Array(
            Array(
              'label' => 'Val B',
              'hideLabel' => true,
              'placeholder' => 'Val B',
              'name' => 'valb',
              'validate' => function($value) {
                $value = trim($value);
                if (!is_numeric($value)) throw new ValidationException("Please enter a numeric value.");
              }
            ),
        )),
        Array(
          'type' => 'html', 
          'htmlOpen' => '<div class="col col-sm-4">',
          'htmlClose'=>'</div>',
          'children'=>Array(
            Array(
              'label' => 'Val C',
              'hideLabel' => true,
              'placeholder' => 'Val C',
              'name' => 'valc',
              'validate' => function($value) {
                $value = trim($value);
                if (!is_numeric($value)) throw new ValidationException("Please enter a numeric value.");
              }
            ),
        )),
      ),
    ),
    
    Array(
      'type' => 'submit',
      'value' => 'Submit',
    ),
);


