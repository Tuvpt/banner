<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Bannerslider
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\BannerSlider\Helper;

use Magento\Framework\App\Helper\Context;
use Mageplaza\Core\Helper\AbstractData;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'bannerslider';

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * Retrieve all configuration options for product slider
     *
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getAllOptions()
    {
        $sliderOptions = '';
        $allConfig     = $this->getModuleConfig('slider_design');
        foreach ($allConfig as $key => $value) {
            if ($key == 'item_slider') {
                $sliderOptions = $sliderOptions . $this->getResponseValue();
            } else if ($key != 'responsive') {
                if(in_array($key, ['loop', 'nav', 'dots', 'lazyLoad', 'autoplay', 'autoplayHoverPause'])){
                    $value = $value ? 'true' : 'false';
                }
                $sliderOptions = $sliderOptions . $key . ':' . $value . ',';
            }
        }

        return '{' . $sliderOptions . '}';
    }

    /**
     * @return bool
     */
    public function isResponsive()
    {
        if ($this->getModuleConfig('slider_design/responsive') == 1) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve responsive values for product slider
     *
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getResponseValue()
    {
        $responsiveOptions = '';
        $responsiveConfig = $this->isResponsive() ? $this->unserialize($this->getModuleConfig('slider_design/item_slider')) : [];

        foreach ($responsiveConfig as $config) {
            if ($config['size'] && $config['items']) {
                $responsiveOptions = $responsiveOptions . $config['size'] . ':{items:' . $config['items'] . '},';
            }
        }

        $responsiveOptions = rtrim($responsiveOptions, ',');

        return 'responsive:{' . $responsiveOptions . '}';
    }
}
