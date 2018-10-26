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

namespace Mageplaza\BannerSlider\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * @method Banner setName($name)
 * @method Banner setUploadFile($uploadFile)
 * @method Banner setUrl($url)
 * @method Banner setType($type)
 * @method Banner setStatus($status)
 * @method mixed getName()
 * @method mixed getUploadFile()
 * @method mixed getUrl()
 * @method mixed getType()
 * @method mixed getStatus()
 * @method Banner setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Banner setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 * @method Banner setSlidersData(array $data)
 * @method array getSlidersData()
 * @method Banner setSlidersIds(array $sliderIds)
 * @method array getSlidersIds()
 * @method Banner setIsChangedSliderList(\bool $flag)
 * @method bool getIsChangedSliderList()
 * @method Banner setAffectedSliderIds(array $ids)
 * @method bool getAffectedSliderIds()
 */
class Banner extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'mageplaza_bannerslider_banner';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'mageplaza_bannerslider_banner';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_bannerslider_banner';

    /**
     * Slider Collection
     * 
     * @var \Mageplaza\BannerSlider\Model\ResourceModel\Slider\Collection
     */
    protected $sliderCollection;

    /**
     * Slider Collection Factory
     * 
     * @var \Mageplaza\BannerSlider\Model\ResourceModel\Slider\CollectionFactory
     */

    protected $imageModel;


    /**
     * constructor
     * 
     * @param \Mageplaza\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
//        \Mageplaza\BannerSlider\Model\ResourceModel\Slider\CollectionFactory $sliderCollectionFactory,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
//        $this->sliderCollectionFactory = $sliderCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\BannerSlider\Model\ResourceModel\Banner');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['type'] = '';
        return $values;
    }

    /**
     * @return array|mixed
     */
//    public function getSlidersPosition()
//    {
//        if (!$this->getId()) {
//            return array();
//        }
//        $array = $this->getData('sliders_position');
//        if (is_null($array)) {
//            $array = $this->getResource()->getSlidersPosition($this);
//            $this->setData('sliders_position', $array);
//        }
//        return $array;
//    }


//    public function getSelectedSlidersCollection()
//    {
//        if (is_null($this->sliderCollection)) {
//            $collection = $this->sliderCollectionFactory->create();
//            $collection->join(
//                'mageplaza_bannerslider_banner_slider',
//                'main_table.slider_id=mageplaza_bannerslider_banner_slider.slider_id AND mageplaza_bannerslider_banner_slider.banner_id='.$this->getId(),
//                ['position']
//            );
//            $collection->addFieldToFilter('status',1);
//
//            $this->sliderCollection = $collection;
//        }
//        return $this->sliderCollection;
//    }

    /**
     * get full image url
     * @return string
     */
    public function getImageUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager  = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl       = $storeManager->getStore()->getBaseUrl();
        return $baseUrl . 'pub/media/mageplaza/bannerslider/banner/image/' . $this->getImage();

    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSliderIds()
    {
        if (!$this->hasData('slider_ids')) {
            $ids = $this->getResource()->getSliderIds($this);

            $this->setData('slider_ids', $ids);
        }

        return (array)$this->getData('slider_ids');
    }
}
