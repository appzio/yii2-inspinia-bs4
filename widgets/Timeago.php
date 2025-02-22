<?php
    /**
     * @link http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license http://www.yiiframework.com/license/
     */
    namespace appzio\inspinia\widgets;

    use Yii;
    use yii\base\InvalidConfigException;
    use yii\helpers\Html;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use appzio\inspinia\TimeagoAsset;
    /**
     *
     * @author Nghia Nguyen <zinzinday@gmail.com>
     * @since 2.0
     */
    class Timeago extends Widget
    {
        /**
         * @var string The html tag to display data time. You can use abbr, span, time ...
         * Default value "time"
         */
        public $tag = 'time';
        /**
         * @var array Options attribute for html tag
         */
        public $options;
        /**
         * @var bool
         */
        public $localTitle = false;
        /**
         * @var string
         * @see https://github.com/rmm5t/jquery-timeago/tree/master/locales
         */
        public $language = 'en';
        /**
         * @var integer Unix timestamp
         */
        public $timestamp;
        private $_assetBundle;
        public function init()
        {
            $this->options['data-toggle'] = ArrayHelper::getValue($this->options, 'data-toggle', 'timeago');
            $this->registerLocale();
            if($this->localTitle) {
                $this->getView()->registerJs("jQuery.timeago.settings.localeTitle = true", 4, 'timeagoSetting');
            }
            $this->getView()->registerJs("jQuery('{$this->tag}[data-toggle=\"{$this->options['data-toggle']}\"]').timeago();", 4, 'timeago');
        }
        protected function registerLocale()
        {
            if (file_exists(Yii::getAlias($this->getAssetBundle()->sourcePath) . DIRECTORY_SEPARATOR . "locales" . DIRECTORY_SEPARATOR . "jquery.timeago.{$this->language}.js")) {
                $this->getAssetBundle()->js[] = "locales/jquery.timeago.{$this->language}.js";
            } else {
                throw new InvalidConfigException("Language '{$this->language}' do not exist.");
            }
        }
        protected function registerAssetBundle()
        {
            $this->_assetBundle = TimeagoAsset::register($this->getView());
        }
        /**
         * @return TimeAgoAsset
         */
        public function getAssetBundle()
        {
            if (!$this->_assetBundle) {
                $this->registerAssetBundle();
            }
            return $this->_assetBundle;
        }
        public function run()
        {
            if ($this->timestamp) {
                echo Html::tag($this->tag,
                    Yii::$app->formatter->asDatetime($this->timestamp),
                    ArrayHelper::merge([
                        'datetime' => Yii::$app->formatter->asDatetime($this->timestamp, "php:c")
                    ], $this->options)
                );
            }
        }
    }