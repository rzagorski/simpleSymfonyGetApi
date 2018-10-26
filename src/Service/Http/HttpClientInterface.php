<?php

declare(strict_types=1);

namespace App\Service\Http;

/** Interface HttpClientInterface */
interface HttpClientInterface
{
    /**
     * @param string $url
     * @param int    $page
     *
     * @return mixed
     */
    public function getJson(string $url, int $page);
}
