# MooForms (pre-alpha)
<a href="http://www.magicalcows.com" target="_blank"><img src="http://www.magicalcows.com/img/magicalcows-logo-sm.jpg" align="right" alt="Magical Cows Logo" height="75" /></a>
This repo is very much a work in progress.  Poke around, but we don't recommend using this in production yet!

## Installation
Until we have a stable release, we will stay out of Packagist, but you can install via composer like so:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/magicalcows/mooforms"
            }
        ],
        "require": {
            "magicalcows/mooforms": "master"
        }
    }

Or clone the repo:

    git clone https://github.com/magicalcows/mooforms.git

## Setup

If you're using composer, then just include `vendor/autoload.php`.

If you download/clone the repo or something, you will need to include `library/Form.php` at minimum.
Depending on your configuration, you might also need to include `library/Exception/ValidationExcpetion.php` as well.
The form will throw a `FormException` if anything is misconfigured, but this should only be seen during development, right? ;)

We will eventually include our own autoloader for folks not using composer.

## Form Configuration

(we're still grazing over this content...)

Quick Notes: when you instantiate a new form, you pass an associative array to the constructor. 
The keys of this array directly correspond to properties of the class.  Have a look at 
the [Form Class](https://github.com/magicalcows/mooforms/blob/master/library/Form.php) to see what you
can set.  Some of them have been documented, we'll get our utters mooving and document the rest soon!
Note: You can even set private properties this way, since the actual setting is done from within the constructor.

## "Field" Configuration

(we're still grazing over this content...)

We used the double quotes on field because in reality, you can configure more than form fields here.

Quick Notes: Field configuration is done via one big array of associative arrays.  Each "sub array" is the configuration
for a field or other component from our library.

### Field Config Options

| Option | Required? | Default | Description |
| --- | --- | --- | --- |
| `type` | No | "text" | The type of component. |
| `name` | No | (value of id if specified) | The value of the name attribute. |
| `id`  | No  | (value of name if specified) | The value of the id attribute.  |
| `label` | No | (none) | Text to use for label. |
| `placeholder` | No | (none - but might change to value of label) | Placeholder text to show in the input. |
| `options` | No | (none) | Array of options for select, radios or checkboxes. (see "Configuring `options`" below) |
| `help` | No | (none) | Help text to show near the field. |
| `addonPre` | No | (none) | Define content of add on to prepend to an input. |
| `addonPost` | No | (none) | Define content of add on to append to an input. |
| `htmlOpen` | When type="html" | (none) | Opening HTML tag(s). Can also be closing - no need to define `htmlClose` or `children`. |
| `htmlClose` | No |  | Closing HTML tags. |
| `children` | No | (none) | Used for type HTML to define additional fields to place inside `htmlOpen` and `htmlClose` tags. |
| `filter` | No | (none) | Anonymous function to call for data filtering. (the pure `validate` option can filter too.) |
| `validate` | No | (none) | Anonymous function to call for validation.  (see "Validating with `validate`" below.) |
| `validateRespect` | No | (none) | Anon function that must return a respect validator.  The validator's assert method will be used.  This options is likely to change, use with caution! |

### Configuring `options` for `select`, `checkboxes` and `radios`.
Array entries with string-based keys will have the key used as the value of the option/radio/checkbox, and the key's value will be the text/label. When the key is an integer then the key's value in the array will be used for both the value of the option/radio/checkbox and the text/label. If you need to use numbers as values, be sure to define them as strings - or if you are getting numeric IDs from a database, cast them to strings: `$record['id'] = (string)$record['id'];`.

### Validating with a `validate` function:
Anonymous validation functions will receive $value, $allFormData, $formInstanceReference. The function should perform validation and throw 
a `MooForms\Exception\ValidationException` for any errors found (with the error text passed to the exception constructor... 
EG: `throw new ValidationException("Please enter your name."));`.  
Must return value on success (allowing validators to act as filters.)

## Happy Grazing!

![grazing](http://images.fineartamerica.com/images-medium-large/beef-cattle-grazing-in-pasture-inga-spence-and-photo-researchers-.jpg "mooooo")

### Moo
