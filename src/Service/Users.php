<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Http\Users\GuzzleHttpClientService;

/** Class Users */
class Users
{
    /**
     * @var GuzzleHttpClientService
     */
    private $usersHttpClientService;

    /**
     * @param GuzzleHttpClientService $usersHttpClientService
     */
    public function __construct(GuzzleHttpClientService $usersHttpClientService)
    {
        $this->usersHttpClientService = $usersHttpClientService;
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function list(int $page = 1): array
    {
        $apiContent = $this->usersHttpClientService->getJson('users', $page);

        $response = $this->prepareResponse($apiContent);

        return $response;
    }

    /**
     * @return array
     */
    public function info(): array
    {
        $apiContent = $this->usersHttpClientService->getJson('users');

        $response = $this->prepareInfoResponse($apiContent);

        return $response;
    }

    /**
     * @param string $apiContent
     *
     * @return array
     */
    private function prepareResponse(string $apiContent): array
    {
        $response = [];
        $contentList = json_decode($apiContent);

        if (isset($contentList->data)) {
            foreach ($contentList->data as $user) {
                $response[] = [
                    'id' => (int) $user->id,
                    'fname' => htmlspecialchars($user->first_name),
                    'lname' => htmlspecialchars($user->last_name),
                    'img' => htmlspecialchars($user->avatar),
                ];
            }
        }

        return $response;
    }

    /**
     * @param string $apiContent
     *
     * @return array
     */
    private function prepareInfoResponse(string $apiContent): array
    {
        $contentList = json_decode($apiContent);

        if (!$contentList) {
            return [];
        }

        $response = [
            'pageSize' => (int) $contentList->per_page,
            'totalCount' => (int) $contentList->total,
            'lessonsCount' => (int) $contentList->total,
            'pageCount' => (int) $contentList->total_pages,
        ];

        return $response;
    }
}
