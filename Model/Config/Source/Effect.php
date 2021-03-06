<?php
/**
 * Mageplaza
 * NOTICE OF LICENSE
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * @category    Mageplaza
 * @package     Mageplaza_BannerSlider
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BannerSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Effect
 * @package Mageplaza\BannerSlider\Model\Config\Source
 */
class Effect implements ArrayInterface
{
    const SLIDER = 'slider';
    const FADEOUT = 'fadeOut';
    const ROTATEOUT = 'rotateOut';
    const FLIPOUT = 'flipOut';
    const ROLLOUT = 'rollOut';
    const SCALE = 'scale';

    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::SLIDER,
                'label' => __('Slider')
            ],
            [
                'value' => self::SCALE,
                'label' => __('Full width slider')
            ],
            [
                'value' => self::FADEOUT,
                'label' => __('fadeOut')
            ],
            [
                'value' => self::ROTATEOUT,
                'label' => __('rotateOut')
            ],
            [
                'value' => self::FLIPOUT,
                'label' => __('flipOut')
            ],
            [
                'value' => self::ROLLOUT,
                'label' => __('rollOut')
            ],

        ];
        return $options;

    }
}