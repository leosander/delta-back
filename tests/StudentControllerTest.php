<?php

namespace App\Controllers;

use App\Models\StudentsModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class StudentControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;
    protected $modelName = StudentsModel::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new StudentsModel(); 
    }
    
    public function testShowStudents()
    {
        $result = $this->withURI('http://localhost:8080/students')
            ->controller(Students::class)
            ->execute('index');

        $this->assertTrue($result->isOK());
    }

    public function testCreateWithValidData()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'photo' => 'johndoe.jpg',
            'address' => '123 Main St',
            'phone' => '555-555-5555'
        ];

        $result = $this->withBody(json_encode($data))
                   ->controller(Students::class)
                   ->execute('create');

        $this->assertTrue($result->isOK());

        $response = $result->response();
        $body = json_decode($response->getBody(), true);
        $this->assertEquals($data, $body);
    }

    public function testCreateWithMissingData()
    {
        $data = [
            'name' => 'John Doe',
            // 'email' is missing
            'photo' => 'johndoe.jpg',
            'address' => '123 Main St',
            'phone' => '555-555-5555'
        ];

        $result = $this->withBody(json_encode($data))
                   ->controller(Students::class)
                   ->execute('create');

        $response = $result->response();
        $statusCode = $response->getStatusCode();

        $this->assertTrue($statusCode >= 400 && $statusCode < 500);

        $body = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('messages', $body);
        $this->assertArrayHasKey('email', $body['messages']);
    }

    public function testCreateWithInvalidData()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'invalid email',  // Invalid email
            'photo' => 'johndoe.jpg',
            'address' => '123 Main St',
            'phone' => '555-555-5555'
        ];

        $result = $this->withBody(json_encode($data))
        ->controller(Students::class)
        ->execute('create');

        $response = $result->response();
        $statusCode = $response->getStatusCode();

        $this->assertTrue($statusCode >= 400 && $statusCode < 500);

        $body = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('messages', $body);
        $this->assertArrayHasKey('email', $body['messages']);
    }
}