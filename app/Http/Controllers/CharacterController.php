<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Services\RickAndMortyService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    public function __construct(
        private readonly RickAndMortyService $service
    ) {}

    public function index(Request $request): ?Response
    {
        $page = (int) $request->query('page', 1);
        $filters = array_filter([
            'name' => $request->query('name'),
            'status' => $request->query('status'),
            'species' => $request->query('species'),
            'gender' => $request->query('gender'),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });

        try {
            $characters = $this->service->getCharacters($filters, $page);

            return Inertia::render('Characters/List', [
                'characters' => $characters['characters'],
                'pagination' => $characters['pagination'],
                'filters' => $filters,
            ]);
        } catch (ApiException $e) {
            return Inertia::render('Characters/List', [
                'characters' => [],
                'pagination' => [],
                'filters' => $filters,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function show(int $id): ?Response
    {
        try {
            $character = $this->service->getCharacter($id);

            return Inertia::render('Characters/Character', [
                'character' => $character,
            ]);
        } catch (ApiException $e) {
            return Inertia::render('Characters/Character', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
