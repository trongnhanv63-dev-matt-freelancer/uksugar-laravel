<?php

namespace App\Domain\Escort\Repositories;

use App\Domain\Escort\Entities\Escort;

interface EscortRepositoryInterface
{
    /**
     * Tìm escort theo ID.
     *
     * @param int $id
     * @return Escort|null
     */
    public function findById(int $id): ?Escort;

    /**
     * Lưu hoặc cập nhật escort (trả về entity đã lưu).
     *
     * @param Escort $escort
     * @return Escort
     */
    public function save(Escort $escort): Escort;

    /**
     * Xóa escort (theo entity).
     *
     * @param Escort $escort
     * @return void
     */
    public function delete(Escort $escort): void;

    /**
     * Phân trang danh sách escorts (mỗi trang $perPage, trang $page).
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage, int $page): array;
}
