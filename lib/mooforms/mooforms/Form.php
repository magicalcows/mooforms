<?php

Namespace MooForms;
require_once __DIR__.'/Exception/FormException.php';
require_once __DIR__.'/Exception/ValidationException.php';
use \MooForms\Exception\FormException;
use \MooForms\Exception\ValidationException;


class Form {

    /**
     *  @var string should contain path to templates 
     */
    public $templateDir = '';

    /**
     *  @var boolean idicates if the feedback system should be used
     */
    public $showFeedback = true;

    /**
     *  @var string Method to use when submitting the form.
     */
    public $method = 'POST';

    /**
     *  @var string|null URI/URL to submit form to, if any (will submit to self if left null.)
     */
    public $action = null;

    /**
     * @var string|null HTML to include above the form fields
     */
    public $formPre = null;

    public $formClass = 'form';

    /**
     * @var string|null HTML to include below the form fields
     */
    public $formFoot = null;

    /**
     * @var array HTML attributes to include on the FORM tag.
     */
    public $formAttributes;
    
    private $_data;

    /**
     * @var boolean Indicates if you are using the Respect Validation library's `assert` method.
     */
    public $respectValidation = false;
    public $respectValidationUsesFirstError = true;

    public $hideLabels = false;
    public $useGrid = false;
    public $novalidate = true;
    public $parsleyUrl = '';
    
    public $horizontalLabelClass = 'col-sm-2';
    public $horizontalInputClass = 'col-sm-5';
    public $horizontalHelpClass = 'col-sm-5';
    

    /**
     * @var array form configuration array (@see docs)
     */
    private $_config;

    /**
     *  @var array Associative array where key = id of element, value = array of options.
     */
    private $_fields = Array();

    /**
     *  @var array Associative array where key = id of field, value = error.
     */
    private $_errors = Array();

    private $_builtHtml = '';
    private $_builtBody = '';


    public function __construct($options = Array(), $config = null) {
        if (!is_array($options)) {
          throw new FormException('Options for BS3Form must be an array.');
      }
      
      if (!empty($options['template'])) {
        $this->setTemplate($options['template']);
        unset($options['template']);
      }

      foreach($options as $k=>$v) {
          if (isset($this->$k)) { $this->$k = $v; }
          else {
            throw new FormException('Invalid BS3Form Option: ' . $k);
        }
      }
      if (empty($this->templateDir)) {
        $this->templateDir = __DIR__ . '/templates/bootstrap3';
      } else

      if (is_array($config)) {
        $this->_config = $config;
      }
      
      $this->_loadData();

    }
    
    public function setTemplate($template) {
        $this->templateDir = __DIR__ . '/templates/' . $template;
    }


    public function setConfig($config) {
      $this->_config = $config;
    }


    public function addError($field, $error) {
        $this->errors[$field] = $error;
        return $this;
    }


    public function output() {
        echo $this->build();
    }


    public function build() {
        if (empty($this->_config)) {
            throw new FormException('Build called before form was configured.');
        }
        if ($this->_builtHtml) { 
          return $this->_builtHtml;
        }
        $this->_loadData();
        $content = $this->getFormBody();
        $html = '<form';
        if ($this->method) { $html .= " method='{$this->method}'"; }
        if ($this->action) { $html .= " action='{$this->action}'"; }
        if ($this->formClass) { $html .= " class='{$this->formClass}'"; }
        if (count($this->formAttributes)) { 
            foreach($this->formAttributes as $k=>$v) {
                $html .= "$k='". $this->_escapeVal($v) . "'";
            }
        }
        if ($this->novalidate) { $html .= ' novalidate'; }
        $html .= '>';
        if ($this->formPre) { $html .= $this->formPre; } 
        $html .= $content;
        if ($this->formFoot) { $html .= $this->formFoot; } 
        $html .= '</form>';
        $this->_builtHtml = $html;
        return $html;

    }
    
    private function _loadData() {
        if (self::isPost()) {
            $this->_data = $_POST;
        } else {
            $this->_data = $_GET;
        }
    }
    public function getFormBody() {
        if (!$this->_builtBody) {
            $this->_builtBody = $this->_build($this->_config);;
        }
        return $this->_builtBody;
    }

    private function _build($config) {
        reset($config);
        $ret = '';
        while(list(,$c) = each($config)) {
            $c['type'] = @$c['type'] ?: 'text';
            $requireIdOrName = Array('text','select','password','email','checkbox','radio','textarea','number','date','datetime',
              'datetime-local','month','week','time','url','search','tel','color');

            if (in_array($c['type'], $requireIdOrName)) {
              if (empty($c['name']) && empty($c['id'])) {
                throw new Error('Type ' . $c['type'] . ' requires "name" or "id" in options to `addInput`.');
                }
                if (empty($c['name'])) {
                    $c['name'] = $c['id'];
                } elseif (empty ($c['id'])) {
                    $c['id'] = $c['name'];
                }
            }

            switch($c['type']) {
                case 'text':      case 'password':
                case 'email':     case 'datetime' :
                case 'number':    case 'datetime-local' :
                case 'date':      case 'week' :
                case 'month':     case 'time' :
                case 'url':       case 'search' :
                case 'tel':       case 'color' :
                    $ret .= $this->_render($c, 'input');
                    break;
                case 'select':    case 'textarea':
                case 'radio':     case 'radios':
                case 'checkbox':  case 'checkboxes':
                case 'submit':
                    $ret .= $this->_render($c, $c['type']);
                    break;
                case 'html':
                    $ret .= $this->_renderTypeHTML($c);
                    break;
                default: 
                    throw new FormException("Invalid config type: {$c['type']}");

            }

        }
        return $ret;
    }
    
    public static function makeKey($str) {
        if (empty($str)) return uniqid('key_');
        return preg_replace('#[ \/]#', '-', 
            preg_replace('#[^A-z0-9_.-]#', '', trim(strtolower($str)))
        );
    }

    private function _render($_config, $_template) {

        $_template_file = $this->templateDir . '/' . $_template . '.phtml';

        $value = $this->_getValue($_config);
        if (isset($_config['filter']) && is_callable($_config['filter'])) {
            $value = $_config['filter']($value);
        }
        
        $key = isset($_config['name']) ? $_config['name'] : @$_config['id'];
        $error = false;
        
        $_isPost = self::isPost();
        
        if ($_isPost && $key) {
          $_showFeedback = $this->showFeedback && !@$_config['addonPost'];
          if (@$this->errors[$key]) {
            $error = $this->errors[$key];
          } elseif (isset($_config['respectValidator'])) {
            if (empty($this->_respectValidator)) {
                require_once __DIR__ . '/Validators/RespectValidator.php';
                $this->_respectValidator = new Validators\RespectValidator();

            }

            list($error,$ex) = $this->_respectValidator->validate($this, $_config, $value);
            if ($error) {
                $this->_errors[$key] = Array('error' => $error,'exception' => $ex);
            }
          } elseif (isset($_config['validate']) && is_callable($_config['validate'])) {
              // standard validation, users write function that returns new value on success or 
              // throw a ValidationException.
              try {
                $value = $_config['validate']($value, $this->_data, $this);
              } catch(ValidationException $e) {
                $error = $e->getMessage();
              } 

          }
        } else {
          $_showFeedback = false;
        }
        
        if ($error && empty($this->_errors[$key])) { 
            $this->_errors[$key] = $error; 
        }

        extract($_config);

        ob_start();
        include $_template_file;
        return ob_get_clean();

    }

    private function _renderTypeHTML($config) {
      if (empty($config['htmlOpen'])) throw new FormException("Field Configuration Key 'htmlOpen' is required for type 'html'");
      $html = $config['htmlOpen'];
      if (isset($config['children'])) {
        $html .= $this->_build($config['children']);
      }
      if (isset($config['htmlClose'])) $html .= $config['htmlClose'];
      return $html;

    }

    private function _renderMultiLineError($errors) {
        $_template_file = $this->templateDir . '/multiline-error.phtml';
        ob_start();
        include $_template_file;
        return ob_get_clean();
    }

    private function _getValue($config) {
      if (!empty($config['value'])) {
        return $config['value'];
      }

      if (!empty($config['name'])) {
        $var = $config['name'];
      } elseif (!empty($config['id'])) {
        $var = $config['id'];
      }
      
      return @$this->_data[$var];

    }
    
    public function getData() {
      if (!$this->_builtHtml) {
        $this->build();
      }
      return $this->_data;
    }

    public function isValid() { 
      if (!$this->_builtHtml) {
        $this->build();
      }
      return count($this->_errors) < 1;
    }

    public function addInput($options) {
        if (empty($options['type'])) $options['type'] = 'text';

        $requireIdOrName = Array('text','select','password','email','checkbox','radio','textarea');

        if (in_array($options['type'], $requireIdOrName)) {
          if (empty($options['name']) && empty($options['id'])) {
            throw new Error('Type ' . $options['type'] . ' requires "name" or "id" in options to `addInput`.');
            }
            if (empty($options['name'])) {
                $options['name'] = $options['id'];
            } elseif (empty ($options['id'])) {
                $options['id'] = $options['name'];
            }
        }

        $this->_config[] = $options;
    }

    static public function _escapeVal($val, $quot = "'") {
        if ($quot == "'") {
            return str_replace("'", "&apos;", $val);
        } else {
            return str_replace('"', "&quot;", $val);
        }
    }

    static public function isPost() {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }





  static public function p($obj, $printAndExit = true) {
      ob_start();
      var_export($obj);
      $str = ob_get_clean();
      $html = '<div class="well" style="text-align:left">' . highlight_string('<?php $obj = ' . $str . '; ?>', true) . '</div>';
      if ($printAndExit) {
        echo $html; exit;
      } else {
        return $html;
      }
  }
}