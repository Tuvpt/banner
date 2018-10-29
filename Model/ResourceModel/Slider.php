<?php
/**
 * Mageplaza_BannerSlider extension
 *                     NOTICE OF LICENSE
 * 
 *                     This source file is subject to the Mageplaza License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 * 
 *                     @category  Mageplaza
 *                     @package   Mageplaza_BannerSlider
 *                     @copyright Copyright (c) 2016
 *                     @license   https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\BannerSlider\Model\ResourceModel;

class Slider extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Date model
     * 
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Banner relation model
     * 
     * @var string
     */
    protected $sliderBannerTable;

    /**
     * Event Manager
     * 
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        $this->date         = $date;
        $this->eventManager = $eventManager;
        parent::__construct($context);
        $this->sliderBannerTable = $this->getTable('mageplaza_bannerslider_banner_slider');
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageplaza_bannerslider_slider', 'slider_id');
    }

    /**
     * Retrieves Slider Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getSliderNameById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'name')
            ->where('slider_id = :slider_id');
        $binds = ['slider_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Mageplaza\BannerSlider\Model\Slider $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        //set default Update At and Create At time post
        $object->setUpdatedAt($this->date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->date());
        }
        $storeIds = $object->getStoreIds();
        if (is_array($storeIds)) {
            $object->setStoreIds(implode(',', $storeIds));
        }

        $groupIds = $object->getCustomerGroupIds();
        if (is_array($groupIds)) {
            $object->setCustomerGroupIds(implode(',', $groupIds));
        }

        return parent::_beforeSave($object);
    }
    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Mageplaza\BannerSlider\Model\Slider $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveBannerRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Mageplaza\BannerSlider\Model\Slider $slider
     * @return array
     */
    public function getBannersPosition(\Mageplaza\BannerSlider\Model\Slider $slider)
    {
        $select = $this->getConnection()->select()->from(
            $this->sliderBannerTable,
            ['banner_id', 'position']
        )
        ->where(
            'slider_id = :slider_id'
        );
        $bind = ['slider_id' => (int)$slider->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Mageplaza\BannerSlider\Model\Slider $slider
     * @return $this
     */
    protected function saveBannerRelation(\Mageplaza\BannerSlider\Model\Slider $slider)
    {
        $slider->setIsChangedBannerList(false);
        $id = $slider->getId();
        $banners = $slider->getBannersData();
        if ($banners === null) {
            return $this;
        }
        $oldBanners = $slider->getBannersPosition();
        $insert = array_diff_key($banners, $oldBanners);
        $delete = array_diff_key($oldBanners, $banners);
        $update = array_intersect_key($banners, $oldBanners);
        $_update = array();
        foreach ($update as $key=>$settings) {
            if (isset($oldBanners[$key]) && $oldBanners[$key] != $settings['position']) {
                $_update[$key] = $settings;
            }
        }
        $update = $_update;
        $adapter = $this->getConnection();
        if (!empty($delete)) {
            $condition = ['banner_id IN(?)' => array_keys($delete), 'slider_id=?' => $id];
            $adapter->delete($this->sliderBannerTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $bannerId => $position) {
                $data[] = [
                    'slider_id' => (int)$id,
                    'banner_id' => (int)$bannerId,
                    'position' => (int)$position['position']
                ];
            }
            $adapter->insertMultiple($this->sliderBannerTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $bannerId => $position) {
                $where = ['slider_id = ?' => (int)$id, 'banner_id = ?' => (int)$bannerId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->sliderBannerTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $bannerIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->eventManager->dispatch(
                'mageplaza_bannerslider_slider_change_banners',
                ['slider' => $slider, 'banner_ids' => $bannerIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $slider->setIsChangedBannerList(true);
            $bannerIds = array_keys($insert + $delete + $update);
            $slider->setAffectedBannerIds($bannerIds);
        }
        return $this;
    }
}
