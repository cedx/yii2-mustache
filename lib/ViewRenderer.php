<?php
/**
 * Implementation of the `yii\mustache\ViewRenderer` class.
 * @module ViewRenderer
 */
namespace yii\mustache;

// Module dependencies.
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\mustache\helpers\FormatHelper;
use yii\mustache\helpers\HtmlHelper;
use yii\mustache\helpers\I18nHelper;

/**
 * View renderer allowing to use the [Mustache](http://mustache.github.io) template syntax.
 * @class yii.mustache.ViewRenderer
 * @extends yii.base.ViewRenderer
 * @constructor
 */
class ViewRenderer extends \yii\base\ViewRenderer {

  /**
   * The identifier of the cache application component that is used to cache the compiled views.
   * If set to `false`, caching is disabled. Defaults to "cache" in production environment, which refers to the primary cache application component.
   * @property cacheId
   * @type string
   * @default "cache"
   */
  public $cacheId=(YII_ENV_PROD ? 'cache' : false);

  /**
   * The time in seconds that the compiled views can remain valid in cache.
   * If set to `0`, the cache never expires.
   * @property cachingDuration
   * @type int
   * @default 0
   */
  public $cachingDuration=0;

  /**
   * Value indicating whether to enable the logging of engine messages.
   * @property enableLogging
   * @type boolean
   * @default false
   */
  public $enableLogging=false;

  /**
   * The underlying Mustache template engine.
   * @property engine
   * @type mustache.Mustache_Engine
   * @private
   */
  private $engine;

  /**
   * The directory or path alias to where the Mustache engine is located.
   * @property enginePath
   * @type string
   * @default "@vendor/mustache/mustache/src/Mustache"
   */
  public $enginePath='@vendor/mustache/mustache/src/Mustache';

  /**
   * Values prepended to the context stack, so they will be available in any view loaded by this instance.
   * Always `null` until the component is fully initialized.
   * @property helpers
   * @type mustache.Mustache_HelperCollection
   */
  private $helpers=[];

  public function getHelpers() {
    return $this->isInitialized ? $this->engine->getHelpers() : null;
  }

  public function setHelpers(array $value) {
    if($this->isInitialized) $this->engine->setHelpers($value);
    else $this->helpers=$value;
  }

  /**
   * Initializes the application component.
   * @method init
   * @throws {yii.base.InvalidCallException} The underlying cache component is invalid.
   */
  public function init() {
    $helpers=[
      'app'=>\Yii::$app,
      'debug'=>YII_DEBUG,
      'format'=>new FormatHelper(),
      'html'=>new HtmlHelper(),
      'i18n'=>new I18nHelper()
    ];

    $options=[
      'charset'=>\Yii::$app->charset,
      'entity_flags'=>ENT_QUOTES | ENT_SUBSTITUTE,
      'escape'=>function($value) { return Html::encode($value); },
      'helpers'=>ArrayHelper::merge($helpers, $this->helpers),
      'partials_loader'=>new Loader(),
      'strict_callables'=>true
    ];

    if(is_string($this->cacheId)) {
      $cache=\Yii::$app->get($this->cacheId);
      if(!$cache instanceof \yii\caching\Cache)
        throw new InvalidCallException(\Yii::t('yii', 'Invalid cache component "{cacheId}".', [ 'cacheId'=>$this->cacheId ]));

      $options['cache']=new Cache($cache, $this->cachingDuration);
    }

    if($this->enableLogging) $options['logger']=new Logger();
    $this->engine=new \Mustache_Engine($options);

    parent::init();
    $this->helpers=[];
  }

  /**
   * Renders a view file.
   * @method render
   * @param {yii.web.View} $view The view object used for rendering the file.
   * @param {string} $file The view file.
   * @param {array} $params The parameters to be passed to the view file.
   * @return {string} The rendering result.
   * @throws {yii.base.InvalidCallException} The specified view file is not found.
   */
  public function render($view, $file, $params) {
    if(!is_file($file)) throw new InvalidCallException(\Yii::t('yii', 'View file "{file}" does not exist.', [ 'file'=>$file ]));
    $values=ArrayHelper::merge([ 'this'=>$view ], is_array($params) ? $params : []);
    return $this->engine->render(@file_get_contents($file), $values);
  }
}
