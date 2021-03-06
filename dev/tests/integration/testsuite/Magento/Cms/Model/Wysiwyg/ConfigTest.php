<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Cms\Model\Wysiwyg;

/**
 * @magentoAppArea adminhtml
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_model;

    protected function setUp()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Config\ScopeInterface'
        )->setCurrentScope(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
        );
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Cms\Model\Wysiwyg\Config'
        );
    }

    /**
     * Tests that config returns valid config array in it
     */
    public function testGetConfig()
    {
        $config = $this->_model->getConfig();
        $this->assertInstanceOf('Magento\Framework\Object', $config);
    }

    /**
     * Tests that config returns right urls going to static js library
     */
    public function testGetConfigJsUrls()
    {
        $config = $this->_model->getConfig();
        $this->assertStringMatchesFormat('http://localhost/pub/lib/%s', $config->getPopupCss());
        $this->assertStringMatchesFormat('http://localhost/pub/lib/%s', $config->getContentCss());
    }

    /**
     * Tests that config doesn't process incoming already prepared data
     *
     * @dataProvider getConfigNoProcessingDataProvider
     */
    public function testGetConfigNoProcessing($original)
    {
        $config = $this->_model->getConfig($original);
        $actual = $config->getData();
        foreach (array_keys($actual) as $key) {
            if (!isset($original[$key])) {
                unset($actual[$key]);
            }
        }
        $this->assertEquals($original, $actual);
    }

    /**
     * @return array
     */
    public function getConfigNoProcessingDataProvider()
    {
        return array(
            array(
                array(
                    'files_browser_window_url' => 'http://example.com/111/',
                    'directives_url' => 'http://example.com/222/',
                    'popup_css' => 'http://example.com/333/popup.css',
                    'content_css' => 'http://example.com/444/content.css',
                    'directives_url_quoted' => 'http://example.com/555/'
                )
            ),
            array(
                array(
                    'files_browser_window_url' => '/111/',
                    'directives_url' => '/222/',
                    'popup_css' => '/333/popup.css',
                    'content_css' => '/444/content.css',
                    'directives_url_quoted' => '/555/'
                )
            ),
            array(
                array(
                    'files_browser_window_url' => '111/',
                    'directives_url' => '222/',
                    'popup_css' => '333/popup.css',
                    'content_css' => '444/content.css',
                    'directives_url_quoted' => '555/'
                )
            )
        );
    }
}
