<?php

namespace App\Event;

use App\Enum\ApiErrorCode;
use App\Security\APIError;
use App\Service\Helper\SerializerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class AccessDeniedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerService $serializerService
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (str_starts_with($event->getRequest()->attributes->get('_route'), 'api_')) {
            $error = $event->getThrowable();
            $errorCodes = [];
            $status = null;

            if ($error instanceof AccessDeniedException) {
                $errorCodes[] = new APIError(
                    ApiErrorCode::GENERIC_UNAUTHORIZED,
                    "Vous êtes déconnecté"
                );
                $status = Response::HTTP_UNAUTHORIZED;
            }

            if (empty($errorCodes)) {
                $errorCodes[] = new APIError(
                    ApiErrorCode::GENERIC_SERVER_ERROR,
                    "erreur non gérée :{$error->getMessage()}"
                );
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            $event->setResponse(
                new JsonResponse(
                    $this->serializerService->toJSON($errorCodes),
                    status: $status,
                    json: true
                )
            );
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 1],
        ];
    }
}
