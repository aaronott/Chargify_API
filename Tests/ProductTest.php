<?php
/**
 * Tests Product class
 *
 * @group chargify
 *
 * @package    Chargify
 * @author     Aaron Ott <aaron.ott@gmail.com>
 * @copyright  2010 Aaron Ott
 */

require_once 'Chargify.php';
require_once 'Chargify/Common.php';
require_once 'Chargify/Product.php';

class Chargify_ProductTest extends PHPUnit_Framework_TestCase
{

  protected $Product;

  public function setUp()
  {
    $this->Product = Chargify::factory('Product');
  }

  /**
   * Provider for getProductById
   *
   * @return array
   */
  function providerProductId()
  {
    return array(
        array(6765),
    );
  }

  /**
   * Provider for getProductByHandle
   *
   * @return array
   */
  function providerProductHandle()
  {
    return array(
        array('level-1'),
    );
  }

  /**
   * Tests Chargify_Product::all
   *
   * @test
   * @covers Chargify_Product::all
   */
  function testProductsAll()
  {
    $list = $this->Product->all();
    $this->assertTrue(is_array($list));
  }

  /**
   * Tests Chargify_Product::by_id
   *
   * @test
   * @dataProvider    providerProductId
   * @covers          Chargify_Product::by_id
   * @param           int     $id     Chargify Product id
   */
  function testById($id)
  {
    $product = $this->Product->by_id($id);
    $this->assertTrue(is_object($product));
  }


  /**
   * Tests Chargify_Product::by_id
   *
   * @test
   * @covers          Chargify_Product::by_id
   * @expectedException Chargify_Exception
   */
  function testByBadId()
  {
    $product = $this->Product->by_id(9999999);
  }

  /**
   * Tests Chargify_Product::by_handle
   *
   * @test
   * @dataProvider  providerProductHandle
   * @covers        Chargify_Product::by_handle
   * @param         string    $handle   Chargify Product Handle
   */
  function testByHandle($handle)
  {
    $product = $this->Product->by_handle($handle);
    $this->assertTrue(is_object($product));
  }

  /**
   * Tests Chargify_Product::by_handle
   *
   * @test
   * @covers        Chargify_Product::by_handle
   * @expectedException Chargify_Exception
   */
  function testByBadHandle()
  {
    $product = $this->Product->by_handle('Does Not Exist');
  }
}

