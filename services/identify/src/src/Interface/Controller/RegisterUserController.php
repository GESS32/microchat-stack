<?php

declare(strict_types=1);

namespace App\Interface\Controller;

use App\Domain\Bus\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RegisterUserController extends AbstractController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    #[Route('/api/v1/users', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload(
            acceptFormat: 'json',
            serializationContext: ['allow_extra_attributes' => false],
            validationGroups: ['create']
        )] RegisterUserRequest $request,
        Request $http
    ): JsonResponse {
        $idempotencyKey = $http->headers->get('Idempotency-Key') ?: null;

        $this->commandBus->dispatch($request->toCommand($idempotencyKey));

        return new JsonResponse(['message' => 'User accepted for registration'], Response::HTTP_ACCEPTED);
    }
}
