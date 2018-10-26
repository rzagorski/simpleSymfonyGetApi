<?php

declare(strict_types=1);

namespace App\Controller\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/** Class ApiController */
class ApiController extends AbstractController
{
    /**
     *  Headers for angular table
     */
    const RESPONSE_HEADERS = [
        'Access-Control-Allow-Headers'=> 'Content-Type',
        'Access-Control-Allow-Methods'=> 'GET',
        'Access-Control-Allow-Origin'=> '*',
    ];

    /**
     * @param int $page
     *
     * @return JsonResponse
     */
    public function index(int $page): JsonResponse
    {
        $res = $this->get('users.service')->list($page);

        return new JsonResponse(['payload' => $res], Response::HTTP_OK, self::RESPONSE_HEADERS);
    }

    /**
     * @return JsonResponse
     */
    public function infoAction(): JsonResponse
    {
        $res = $this->get('users.service')->info();

        return new JsonResponse($res, Response::HTTP_OK, self::RESPONSE_HEADERS);
    }
}
