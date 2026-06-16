<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;

final readonly class RickAndMortyService
{
    public function __construct(
        private RickAndMortyClient $client
    ) {}

    public function getCharacters(array $filters = [], int $page = 1): array
    {
        $params = array_merge(['page' => $page], array_filter($filters));
        $response = $this->client->get('/character', $params);

        $characters = array_map(static function ($charData) {
            return Character::fromApiResponse($charData);
        }, $response['results'] ?? []);

        return [
            'characters' => $characters,
            'pagination' => $response['info'] ?? [],
        ];
    }

    public function getCharacter(int $id): Character
    {
        $response = $this->client->get("/character/{$id}");

        return Character::fromApiResponse($response);
    }
}
