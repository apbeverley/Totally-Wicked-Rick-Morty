export interface Character {
    id: number;
    name: string;
    status: string;
    species: string;
    type: string;
    gender: string;
    origin: string;
    location: string;
    image: string;
    episodes: number[];
    url: string;
    created: string;
}

export interface Pagination {
    count: number;
    pages: number;
    next: string | null;
    prev: string | null;
}
