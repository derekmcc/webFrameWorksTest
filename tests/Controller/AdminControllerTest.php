<?php
/**
 * Created by PhpStorm.
 * User: Derek
 * Date: 29/03/2018
 * Time: 19:37
 */

namespace App\Tests\Controller;


use App\Controller\AdminController;
use PHPUnit\Framework\TestCase;

class AdminControllerTest extends TestCase
{
    public function testCanCreateObject()
    {
        // Arrange
        $adminController = new AdminController();

        // Act

        // Assert
        $this->assertNotNull($adminController);
    }
}