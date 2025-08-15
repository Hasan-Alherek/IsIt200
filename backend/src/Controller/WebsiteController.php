<?php

namespace App\Controller;

use App\Entity\Website;
use App\Manager\WebsiteManager;
use App\Repository\WebsiteRepository;
use App\Service\WebsiteValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


final class WebsiteController extends AbstractController
{
    public function __construct(WebsiteManager $websiteManager)
    {
        $this->websiteManager = $websiteManager;
    }

    #[Route('/', name: 'app_website', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $data = $this->websiteManager->getAllWebsites();
        if(empty($data)) {
            return new JsonResponse(
                [
                    'message' => 'No websites were found',
                    'response' => JsonResponse::HTTP_NO_CONTENT
                ],
                JsonResponse::HTTP_NO_CONTENT
            );
        }
        return new JsonResponse(
            [
                'data' => $data,
                'message' => 'Websites are fetched',
                'response' => JsonResponse::HTTP_OK
            ],
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/addWebsite', name: 'add_website', methods: ['POST'])]
    public function addWebsite(
        Request $request,
        WebsiteValidator $websiteValidator,
    ): JsonResponse
    {
        $name = $request->query->get('name');
        $url = $request->query->get('url');
        if(
            !$websiteValidator->validateName($name) ||
            !$websiteValidator->validateUrl($url)
        )
        {
            return new JsonResponse(
                [
                    'message' => 'Invalid name or url',
                    'response' => JsonResponse::HTTP_BAD_REQUEST
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $this->websiteManager->addWebsite($name, $url);
        return new JsonResponse(
            [
                'message' => 'Website added',
                'response' => JsonResponse::HTTP_CREATED
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
