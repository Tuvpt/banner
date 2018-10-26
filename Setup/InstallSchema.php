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
namespace Mageplaza\BannerSlider\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageplaza_bannerslider_banner')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('mageplaza_bannerslider_banner')
            )
            ->addColumn(
                'banner_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true
                ],
                'Banner ID'
            )
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Banner Name')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Banner Status')
            ->addColumn('type', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Banner Type')
            ->addColumn('image', Table::TYPE_TEXT, 255, [], 'Banner Image')
            ->addColumn('url_banner', Table::TYPE_TEXT, 255, [], 'Banner Url'
            )->addColumn('url_video', Table::TYPE_TEXT, 255, [], 'Video Url')
            ->addColumn('title', Table::TYPE_TEXT, 255, [], 'Title')
            ->addColumn('newtab', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Open new tab')
            ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Banner Created At')
            ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Banner Updated At')
            ->setComment('Banner Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('mageplaza_bannerslider_banner'),
                $setup->getIdxName(
                    $installer->getTable('mageplaza_bannerslider_banner'),
                    ['name','image','url_banner'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name','image','url_banner'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        if (!$installer->tableExists('mageplaza_bannerslider_slider')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('mageplaza_bannerslider_slider')
            )
            ->addColumn(
                'slider_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true
                ],
                'Slider ID'
            )
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable => false'], 'Slider Name')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Slider Status')
            ->addColumn('location', Table::TYPE_TEXT, 255, [], 'Location')
            ->addColumn('store_ids', Table::TYPE_TEXT, 255, [])
            ->addColumn('customer_group_ids', Table::TYPE_TEXT, 255, [])
            ->addColumn('effect',Table::TYPE_INTEGER, null, ['nullable' => false, 'default' => '0'], 'Animation effect')
            ->addColumn('width', Table::TYPE_TEXT, 255, [], 'Width')
            ->addColumn('height', Table::TYPE_TEXT, 255, [], 'Height')
            ->addColumn('design',Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Manually design')
            ->addColumn('loop',Table::TYPE_SMALLINT, null, [], 'Loop slider')
            ->addColumn('lazyload',Table::TYPE_SMALLINT, null, [], 'Lazyload image')
            ->addColumn('autoplay',Table::TYPE_SMALLINT, null, [], 'Autoplay')
            ->addColumn('autoplayTimeout',Table::TYPE_SMALLINT, null, [], 'autoplay Timeout')
            ->addColumn('nav',Table::TYPE_SMALLINT, null, [], 'Navigation')
            ->addColumn('dots',Table::TYPE_SMALLINT, null, [], 'Dots')
            ->addColumn('is_responsive', Table::TYPE_SMALLINT, null, [], 'Responsive')
            ->addColumn('responsive_items', Table::TYPE_TEXT, 255, [], 'Max Items slider')
            ->addColumn('from_date', Table::TYPE_DATE, null, ['nullable' => true, 'default' => null], 'From')
            ->addColumn('to_date', Table::TYPE_DATE, null, ['nullable' => true, 'default' => null], 'To')
            ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [], 'Slider Created At')
            ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, [], 'Slider Updated At')
            ->setComment('Slider Table');

            $installer->getConnection()->createTable($table);

        }
        if (!$installer->tableExists('mageplaza_bannerslider_banner_slider')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageplaza_bannerslider_banner_slider'));
            $table->addColumn(
                'slider_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'   => true,
                ],
                'Slider ID'
            )
            ->addColumn(
                'banner_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'   => true,
                ],
                'Banner ID'
            )
            ->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'default' => '0'
                ],
                'Position'
            )
            ->addIndex(
                $installer->getIdxName('mageplaza_bannerslider_banner_slider', ['slider_id']),
                ['slider_id']
            )
            ->addIndex(
                $installer->getIdxName('mageplaza_bannerslider_banner_slider', ['banner_id']),
                ['banner_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'mageplaza_bannerslider_banner_slider',
                    'slider_id',
                    'mageplaza_bannerslider_slider',
                    'slider_id'
                ),
                'slider_id',
                $installer->getTable('mageplaza_bannerslider_slider'),
                'slider_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'mageplaza_bannerslider_banner_slider',
                    'banner_id',
                    'mageplaza_bannerslider_banner',
                    'banner_id'
                ),
                'banner_id',
                $installer->getTable('mageplaza_bannerslider_banner'),
                'banner_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->addIndex(
                $installer->getIdxName(
                    'mageplaza_bannerslider_banner_slider',
                    [
                        'slider_id',
                        'banner_id'
                    ],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [
                    'slider_id',
                    'banner_id'
                ],
                [
                    'type' => AdapterInterface::INDEX_TYPE_UNIQUE
                ]
            )
            ->setComment('Slider To Banner Link Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
