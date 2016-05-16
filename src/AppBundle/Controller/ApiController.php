<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    /**
     * @Route("/api")
     */
    public function apiPage()
    {
        return new JsonResponse(
            [
                'URLs' => [
                    '/api' => 'Shows this page',
                    '/flyers' => 'Lists all current sales flyers',
                    '/flyer/$id' => 'Get info about a specific sales flyer',
                ],
            ]
        );
    }
}

