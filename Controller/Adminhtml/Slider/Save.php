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
 * @package     Mageplaza_BannerSlider
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\BannerSlider\Controller\Adminhtml\Slider;

class Save extends \Mageplaza\BannerSlider\Controller\Adminhtml\Slider
{

    /**
     * JS helper
     * 
     * @var \Magento\Backend\Helper\Js
     */
    protected $jsHelper;

    /**
     * constructor
     * 
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Mageplaza\BannerSlider\Model\SliderFactory $sliderFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Helper\Js $jsHelper,
        \Mageplaza\BannerSlider\Model\SliderFactory $sliderFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->jsHelper       = $jsHelper;
        parent::__construct($sliderFactory, $registry, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('slider')) {
            $slider = $this->initSlider();
            $slider->addData($data);
//            $banners = $this->getRequest()->getPost('banners', -1);
//            if ($banners != -1) {
//                $slider->setBannersData($this->jsHelper->decodeGridSerializedInput($banners));
//            }
            $this->_eventManager->dispatch(
                'mpbannerslider_slider_prepare_save',
                [
                    'slider' => $slider,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $slider->save();
                $this->messageManager->addSuccess(__('The Slider has been saved.'));
                $this->_session->setMageplazaBannerSliderSliderData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'mpbannerslider/*/edit',
                        [
                            'slider_id' => $slider->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('mpbannerslider/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Slider.'));
            }
            $this->_getSession()->setMageplazaBannerSliderSliderData($data);
            $resultRedirect->setPath(
                'mpbannerslider/*/edit',
                [
                    'slider_id' => $slider->getId(),
                    '_current' => true
                ]
            );

            return $resultRedirect;
        }

        $resultRedirect->setPath('mpbannerslider/*/');

        return $resultRedirect;
    }
}
