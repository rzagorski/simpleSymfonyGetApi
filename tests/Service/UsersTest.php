<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Service;

use App\Service\Http\Users\GuzzleHttpClientService;
use App\Service\Users;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    const VERY_LAST_PAGE = 9999;
    const FIRST_PAGE = 1;
    const CORRECT_API_RESPONSE = [
        'page' => 1,
        'per_page' => 3,
        'total' => 12,
        'total_pages' => 4,
        'data' => [
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe', 'avatar' => 'https://s3.amazonaws.com/uifaces/faces/twitter/marcoramires/128.jpg'],
            ['id' => 2, 'first_name' => 'Dave', 'last_name' => 'Sparrowleg', 'avatar' => 'https://s3.amazonaws.com/uifaces/faces/twitter/stephenmoon/128.jpg'],
            ['id' => 3, 'first_name' => 'Jonny', 'last_name' => 'Johansson', 'avatar' => 'https://s3.amazonaws.com/uifaces/faces/twitter/bigmancho/128.jpg'],
        ]
    ];

    /**
     * @var GuzzleHttpClientService|MockObject
     */
    private $usersHttpClientServiceMock;

    /** @var Users */
    private $sut;

    protected function setUp()
    {
        $this->usersHttpClientServiceMock = $this->createMock(GuzzleHttpClientService::class);

        $this->sut = new Users(
            $this->usersHttpClientServiceMock
        );
    }

    /**
     * @test
     */
    public function list_WillReturnEmptyList()
    {
        $this->usersHttpClientServiceMock->expects($this->any())
            ->method('getJson')
            ->with('users', self::VERY_LAST_PAGE)
            ->willReturn('[]');

        $response = $this->sut->list(self::VERY_LAST_PAGE);

        $this->assertSame([], $response);
    }

    /**
     * @test
     */
    public function list_WillReturnListWithTreeElements()
    {
        $this->usersHttpClientServiceMock->expects($this->any())
            ->method('getJson')
            ->with('users', self::FIRST_PAGE)
            ->willReturn(json_encode(self::CORRECT_API_RESPONSE));

        $response = $this->sut->list(self::FIRST_PAGE);

        $this->assertCount(3, $response);
        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('lname', $response[0]);
        $this->assertArrayHasKey('fname', $response[0]);
    }

    /**
     * @test
     */
    public function info_WillReturnListWithFourElements()
    {
        $this->usersHttpClientServiceMock->expects($this->any())
            ->method('getJson')
            ->with('users')
            ->willReturn(json_encode(self::CORRECT_API_RESPONSE));

        $response = $this->sut->info();

        $this->assertCount(4, $response);
        $this->assertArrayHasKey('pageSize', $response);
        $this->assertArrayHasKey('totalCount', $response);
        $this->assertArrayHasKey('lessonsCount', $response);
        $this->assertArrayHasKey('pageCount', $response);
    }

    /**
     * @test
     */
    public function info_WillReturnEmptyList()
    {
        $this->usersHttpClientServiceMock->expects($this->any())
            ->method('getJson')
            ->with('users')
            ->willReturn('Test incorrect string');

        $response = $this->sut->info();

        $this->assertCount(0, $response);
    }
}
