<?php

namespace App\Domain\Escort\Repositories;

use App\Domain\Escort\Entities\EscortEntity;

interface EscortRepositoryInterface
{
    /**
     * Tìm escort theo ID.
     *
     * @param int $id
     * @return EscortEntity|null
     */
    public function findById(int $id): ?EscortEntity;

    /**
     * Lưu hoặc cập nhật escort (trả về entity đã lưu).
     *
     * @param EscortEntity $escort
     * @return EscortEntity
     */
    public function save(EscortEntity $escort): EscortEntity;

    /**
     * Xóa escort (theo entity).
     *
     * @param EscortEntity $escort
     * @return void
     */
    public function delete(EscortEntity $escort): void;

    /**
     * Phân trang danh sách escorts (mỗi trang $perPage, trang $page).
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage, int $page): array;
}
