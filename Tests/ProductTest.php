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
   * Tests Chargify_Product::listProducts
   *
   * @test
	 * @covers Chargify_Product::listProducts
	 */
  function testListProducts()
  {
    $list = $this->Product->listProducts();
    $this->assertTrue(is_array($list));
  }
  
  /**
   * Tests Chargify_Product::getProductById
   *
   * @test
   * @dataProvider    providerProductId
   * @covers          Chargify_Product::getProductById
   * @param           int     $id     Chargify Product id
   */
  function testGetProductById($id)
  {
    $product = $this->Product->getProductById($id);
    $this->assertTrue(is_object($product));
  }


  /**
   * Tests Chargify_Product::getProductById
   *
   * @test
   * @covers          Chargify_Product::getProductById
   * @expectedException Chargify_Exception
   */
  function testGetProductByBadId()
  {
    $product = $this->Product->getProductById(9999999);
  }
  
  /**
   * Tests Chargify_Product::getProductByHandle
   *
   * @test
   * @dataProvider  providerProductHandle
   * @covers        Chargify_Product::getProductByHandle
   * @param         string    $handle   Chargify Product Handle
   */
  function testGetProductByHandle($handle)
  {
    $product = $this->Product->getProductByHandle($handle);
    $this->assertTrue(is_object($product));
  }
  
  /**
   * Tests Chargify_Product::getProductByHandle
   *
   * @test
   * @covers        Chargify_Product::getProductByHandle
   * @expectedException Chargify_Exception
   */
  function testGetProductByBadHandle()
  {
    $product = $this->Product->getProductByHandle('Does Not Exist');
  }
}
  