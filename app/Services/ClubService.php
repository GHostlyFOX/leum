<?php

namespace App\Services;

use App\Models\Club;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ClubService
{
    /**
     * Список клубов с пагинацией.
     */
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

    /**
     * Получить клуб по ID.
     */
    public function find(int $id): Club
    {
        return Club::with([
            'sportType', 'clubType', 'country', 'city',
            'teams', 'venues', 'trainingTypes',
        ])->findOrFail($id);
    }

    /**
     * Создать клуб.
     */
    public function create(array $data, ?UploadedFile $logo = null): Club
    {
        return DB::transaction(function () use ($data, $logo) {
            if ($logo) {
                $data['logo_file_id'] = $this->storeLogo($logo);
            }

            return Club::create($data);
        });
    }

    /**
     * Обновить клуб.
     */
    public function update(Club $club, array $data, ?UploadedFile $logo = null): Club
    {
        return DB::transaction(function () use ($club, $data, $logo) {
            if ($logo) {
                $data['logo_file_id'] = $this->storeLogo($logo);
            }

            $club->update($data);
            return $club->fresh();
        });
    }

    /**
     * Удалить клуб.
     */
    public function delete(Club $club): void
    {
        $club->delete();
    }

    /**
     * Сохранить логотип в files.
     */
    private function storeLogo(UploadedFile $file): int
    {
        $path = $file->store('clubs', 'public');

        $fileModel = File::create([
            'path'       => $path,
            'mime_type'  => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);

        return $fileModel->id;
    }
}
