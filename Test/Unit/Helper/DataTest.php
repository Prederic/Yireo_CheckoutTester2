<?php
/**
 * Yireo CheckoutTester2 for Magento
 *
 * @package     Yireo_CheckoutTester2
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2017 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\CheckoutTester2\Test\Unit\Helper;

use PHPUnit_Framework_MockObject_MockObject;
use \Yireo\CheckoutTester2\Helper\Data as Target;

/**
 * Class DataTest
 *
 * @package Yireo\CheckoutTester2\Test\Unit\Helper
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var array
	 */
	protected $scopeConfigValues = [];

	/**
	 * Test whether the enabled flag works
	 */
	public function testEnabled()
	{
		$this->setScopeConfigValue('checkouttester2/settings/enabled', 1);
		$target = $this->getTargetObject();
		$this->assertSame(true, $target->enabled());

		$this->setScopeConfigValue('checkouttester2/settings/enabled', 0);
		$target = $this->getTargetObject();
		$this->assertSame(false, $target->enabled());
	}

	/**
	 * Test whether the URL returns some value
	 */
	public function testHasAccess()
	{
        $this->setScopeConfigValue('checkouttester2/settings/ip', '127.0.0.1');
        $target = $this->getTargetObject();
        $this->assertSame(true, $target->hasAccess());
	}

    /**
     * Test whether the URL returns some value
     */
    public function testGetOrderIdFromConfig()
    {
        $this->setScopeConfigValue('checkouttester2/settings/order_id', '42');
        $target = $this->getTargetObject();
        $this->assertSame(42, $target->getOrderIdFromConfig());
        $this->assertNotSame(41, $target->getOrderIdFromConfig());
    }

    /**
     * Test whether the URL returns some value
     */
    public function testAllowDispatchCheckoutOnepageControllerSuccessAction()
    {
        $this->setScopeConfigValue('checkouttester2/settings/checkout_onepage_controller_success_action', 1);
        $target = $this->getTargetObject();
        $this->assertSame(true, $target->allowDispatchCheckoutOnepageControllerSuccessAction());

        $this->setScopeConfigValue('checkouttester2/settings/checkout_onepage_controller_success_action', 0);
        $target = $this->getTargetObject();
        $this->assertSame(false, $target->allowDispatchCheckoutOnepageControllerSuccessAction());
    }

	/**
	 * @return Target
	 */
	protected function getTargetObject()
	{
		$context = $this->getContextMock();

		$target = new Target($context);

		return $target;
	}

	/**
	 * @return \Magento\Framework\App\Helper\Context
	 */
	protected function getContextMock()
	{
		$context = $this->createMock(
			'Magento\Framework\App\Helper\Context',
			[],
			[],
			'',
			false,
			false
		);

		$scopeConfig = $this->getScopeConfigMock();
		$context->expects($this->any())
			->method('getScopeConfig')
			->will($this->returnValue($scopeConfig)
			);

		$request = $this->getRequestMock();
		$context->expects($this->any())
			->method('getRequest')
			->will($this->returnValue($request)
			);

		return $context;
	}

	/**
	 * @return \Magento\Framework\App\Config\ScopeConfigInterface
	 */
	protected function getScopeConfigMock()
	{
		$scopeConfig = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');

		$scopeConfig->expects($this->any())
			->method('getValue')
			->will($this->returnValueMap($this->getScopeConfigValues()));

		return $scopeConfig;
	}

	/**
	 * @return \Magento\Framework\App\Request\Http
	 */
	protected function getRequestMock()
	{
		$request = $this->createMock('Magento\Framework\App\Request\Http',
			[],
			[],
			'',
			false,
			false
		);

        $request->expects($this->any())
            ->method('getClientIp')
            ->willReturn('127.0.0.1');

		return $request;
	}

    /**
     * @return array
     */
	protected function getScopeConfigValues()
    {
        return array_values($this->scopeConfigValues);
    }

	/**
	 * @return array
	 */
	protected function setScopeConfigValue($name, $value)
	{
	    $scope = 'store';
		$this->scopeConfigValues[$name] = [$name, $scope, null, $value];
	}
}