<?php

namespace Modules\Club\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Club\Models\Club;
use Modules\File\Services\FileService;

class ClubService
{
    public function __construct(
        private readonly FileService $fileService
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Club::query()
            ->with(['sportType', 'clubType', 'country', 'city']);

        if (! empty($filters['sport_type_id'])) {
            $query->where('sport_type_id', $filters['sport_type_id']);
        }
        if (! empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }
        if (! empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }
        if (! empty($filters['search'])) {
            $query->where('name', 'ilike', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $id): Club
    {
        return Club::with([
            'sportType', 'clubType', 'country', 'city',
            'teams', 'venues', 'trainingTypes',
        ])->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $logo = null): Club
    {
        return DB::transaction(function () use ($data, $logo) {
            if ($logo) {
                $file = $this->fileService->uploadPublic($logo, 'clubs');
                $data['logo_file_id'] = $file->id;
            }

            return Club::create($data);
        });
    }

    public function update(Club $club, array $data, ?UploadedFile $logo = null): Club
    {
        return DB::transaction(function () use ($club, $data, $logo) {
            if ($logo) {
                $file = $this->fileService->uploadPublic($logo, 'clubs');
                $data['logo_file_id'] = $file->id;
            }

            $club->update($data);
            return $club->fresh();
        });
    }

    public function delete(Club $club): void
    {
        $club->delete();
    }
}
