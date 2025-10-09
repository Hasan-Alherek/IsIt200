<?php

namespace App\Controller;

use App\Manager\WebsiteManager;
use App\Service\WebsiteValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


final class WebsiteController extends AbstractController
{
    public function __construct(
        private WebsiteManager $websiteManager,
    )
    {}

    #[Route('/', name: 'app_websites', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $data = $this->websiteManager->getAllWebsites();
        return new JsonResponse(
            [
                'status' => 'success',
                'message' => 'Websites are fetched',
                'code' => JsonResponse::HTTP_OK,
                'data' => $data
            ],
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/website/{id}', name: 'website_show', methods: ['GET'])]
    public function show(
        int $id,
    ): JsonResponse
    {
        $data = $this->websiteManager->getWebsite($id);
        return new JsonResponse(
            [
                'status' => 'success',
                'message' => 'Website is fetched',
                'code' => JsonResponse::HTTP_OK,
                'data' => $data
            ],
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/website/add', name: 'website_add', methods: ['POST'])]
    public function add(
        Request $request,
        WebsiteValidator $websiteValidator,
    ): JsonResponse
    {
        $name = $request->query->get('name');
        $url = $request->query->get('url');
        $websiteValidator->validateName($name);
        $websiteValidator->validateUrl($url);
        $this->websiteManager->addWebsite($name, $url);
        return new JsonResponse(
            [
                'message' => 'Website added',
                'code' => JsonResponse::HTTP_CREATED
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route('/website/{id}', name: 'website_edit', methods: ['PUT', 'PATCH'])]
    public function edit(
        Request $request,
        WebsiteValidator $websiteValidator,
        int $id,
    ): JsonResponse
    {
        $name = $request->query->get('name');
        $url = $request->query->get('url');
        $websiteValidator->validateName($name);
        $websiteValidator->validateUrl($url);
        $this->websiteManager->editWebsite($id, $name, $url);
        return new JsonResponse(
            [
                'status' => 'success',
                'message' => "Website with the id: $id updated",
                'code' => JsonResponse::HTTP_OK
            ],
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/website/{id}', name: 'website_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
    ): JsonResponse
    {
        $this->websiteManager->deleteWebsite($id);
        return new JsonResponse(
            [
                'status' => 'success',
                'message' => "Website with the id: $id deleted",
                'code' => JsonResponse::HTTP_OK
            ],
            JsonResponse::HTTP_OK
        );
    }
}
