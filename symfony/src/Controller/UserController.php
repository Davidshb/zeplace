<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Security(name: 'Bearer')]
#[OA\Tag(name: 'User')]
#[Route('/api/user', name: 'api_user_')]
class UserController extends AbstractApiController
{
}
