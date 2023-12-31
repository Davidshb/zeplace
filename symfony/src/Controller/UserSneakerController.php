<?php

namespace App\Controller;

use App\Dto\Input\UserSneakerDTO;
use App\Entity\User;
use App\Entity\UserSneaker;
use App\Exception\MultipleApiError;
use App\Repository\UserSneakerRepository;
use App\Security\Voter\UserSneakerVoter;
use App\Service\CacheKeyService;
use App\Service\Helper\SerializerService;
use App\Service\Hydrator\DTOHydrator;
use App\Service\UserSneakerService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Security(name: 'Bearer')]
#[Route('/api/sneaker', name: 'api_sneaker_')]
#[OA\Tag(name: 'Sneaker')]
class UserSneakerController extends AbstractApiController
{
    public function __construct(
        SerializerService $serializerService,
        private readonly UserSneakerRepository $userSneakerRepository,
        private readonly UserSneakerService $userSneakerService
    ) {
        parent::__construct($serializerService);
    }

    protected function denyAccessUnlessGranted(
        mixed $attribute,
        mixed $subject = null,
        string $message = "Vous n'avez pas accès à cette paire"
    ): void {
        parent::denyAccessUnlessGranted($attribute, $subject, $message);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/sold', name: 'list_sold', methods: 'GET')]
    public function listSold(
        TagAwareCacheInterface $cache,
        CacheKeyService $cacheKeyService,
        #[CurrentUser] User $user
    ): JsonResponse {

        $shoes = $cache->get(
            $cacheKeyService->getSoldShoesCacheKey($user->getId()),
            function (ItemInterface $item) use ($user, $cacheKeyService) {
                $item->tag(CacheKeyService::SOLD_SHOES_BASE_KEY)
                     ->tag($cacheKeyService->getUserTag($user->getId()));

                $userSneakers = $this->userSneakerRepository->findSoldByUser($user);

                return $this->serializerService->toJSON(
                    $this->userSneakerService->computeSoldShoes($userSneakers)
                );
            }
        );

        return new JsonResponse($shoes, json: true);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/sale', name: 'list_sale', methods: 'GET')]
    public function listSale(
        TagAwareCacheInterface $cache,
        CacheKeyService $cacheKeyService,
        #[CurrentUser] User $user
    ): JsonResponse {
        $shoes = $cache->get(
            $cacheKeyService->getSaleShoesCacheKey($user->getId()),
            function (ItemInterface $item) use ($user, $cacheKeyService) {
                $item->tag(CacheKeyService::SALE_SHOES_BASE_KEY)
                     ->tag($cacheKeyService->getUserTag($user->getId()));

                $userSneakers = $this->userSneakerRepository->findSaleByUser($user);

                return $this->serializerService->toJSON(
                    $this->userSneakerService->computeSaleShoes($userSneakers)
                );
            }
        );

        return new JsonResponse($shoes, json: true);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function deleteUserSneaker(?UserSneaker $userSneaker, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($userSneaker === null) {
            return $this->getNotFoundError(message: 'la sneaker est introuvable');
        }

        $this->denyAccessUnlessGranted(UserSneakerVoter::USER_SNEAKER_EDIT, $userSneaker);

        //$entityManager->remove($userSneaker);
        //$entityManager->flush();

        return $this->getOk();
    }

    #[Route('/{id}', name: 'get', methods: 'GET')]
    public function getEditSneaker(?UserSneaker $userSneaker): JsonResponse
    {
        if ($userSneaker === null) {
            return $this->getNotFoundError(message: 'la sneaker est introuvable');
        }

        $this->denyAccessUnlessGranted(UserSneakerVoter::USER_SNEAKER_EDIT, $userSneaker);

        return new JsonResponse(
            $this->serializerService->toJSON($this->userSneakerService->computeEditUserSneaker($userSneaker)),
            json: true
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: UserSneakerDTO::class),
            type: 'object'
        )
    )]
    public function editSneaker(
        Request $request,
        ?UserSneaker $userSneaker,
        DTOHydrator $DTOHydrator,
        EntityManagerInterface $entityManager,
        TagAwareCacheInterface $cache,
        #[CurrentUser] User $user,
        CacheKeyService $cacheKeyService
    ): JsonResponse {
        if ($userSneaker === null) {
            return $this->getNotFoundError(message: 'la sneaker est introuvable');
        }

        $this->denyAccessUnlessGranted(UserSneakerVoter::USER_SNEAKER_EDIT, $userSneaker);

        try {
            $dto = $DTOHydrator->hydrateFromJson($request->getContent(), UserSneakerDTO::class);
            $DTOHydrator->hydrateFromDto($dto, $userSneaker);
        } catch (MultipleApiError $e) {
            return $this->getError($e->getApiErrors(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            dump($e);
            return $this->getBadRequest(message: $e->getMessage());
        }

        $entityManager->flush();

        $cache->invalidateTags([$cacheKeyService->getUserTag($user->getId())]);

        return $this->getOk();
    }
}
