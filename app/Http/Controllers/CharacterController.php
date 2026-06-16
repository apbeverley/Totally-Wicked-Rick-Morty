<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\RickAndMortyService;
use Illuminate\Http\Request;
use Inertia\Response;

class CharacterController extends Controller
{
    public function __construct(
        private readonly RickAndMortyService $service
    ) {}

    public function index(Request $request): ?Response
    {

    }

    public function show(int $id): ?Response
    {

    }
}
