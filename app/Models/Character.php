<?php

declare(strict_types=1);

namespace App\Models;

readonly class Character
{
    public int $id;

    public string $name;

    public string $species;

    public string $status;

    public string $gender;

    public string $origin;

    public string $image;

    public array $episodes;

    public function __construct(
        int $id,
        string $name,
        string $species,
        string $status,
        string $gender,
        string $origin,
        string $image,
        array $episodes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->species = $species;
        $this->status = $status;
        $this->gender = $gender;
        $this->origin = $origin;
        $this->image = $image;
        $this->episodes = $episodes;
    }

    public static function fromApiResponse(array $data): self
    {
        $episodes = array_map(static function (string $url) {
            return (int) basename($url);
        }, $data['episode'] ?? []);

        return new self(
            $data['id'],
            $data['name'],
            $data['species'],
            $data['status'],
            $data['gender'],
            $data['location']['name'] ?? 'Unknown',
            $data['image'],
            $episodes
        );
    }
}
