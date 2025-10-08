<?php

namespace App\EventSubscriber;


use App\Exception\BadRequestException;
use App\Exception\NoContentException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = $this->mapExceptionToResponse($exception);
        $event->setResponse($response);
    }

    private function mapExceptionToResponse(\Throwable $exception): JsonResponse
    {
        return match (true) {
            $exception instanceof NoContentException => new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                ],
                $exception->getCode()
            ),
            $exception instanceof BadRequestException => new JsonResponse(
                [
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ],
                $exception->getCode()
            ),
            $exception instanceof UniqueConstraintViolationException => new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'The Website already exists.',
                    'code' => JsonResponse::HTTP_CONFLICT
                ],
                JsonResponse::HTTP_CONFLICT
            ),
            $exception instanceof  ORMException => new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'A database error occurred.',
                    'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            ),
            default => new JsonResponse([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR),
        };
    }
}