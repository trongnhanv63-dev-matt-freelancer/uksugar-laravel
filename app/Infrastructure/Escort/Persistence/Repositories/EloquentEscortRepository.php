<?php

namespace App\Infrastructure\Escort\Persistence\Repositories;

use App\Domain\Escort\Entities\Escort;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;
use App\Infrastructure\Escort\Persistence\Eloquent\EscortModel;

class EloquentEscortRepository implements EscortRepositoryInterface
{
    // Tìm Escort theo ID
    public function findById(int $id): ?Escort
    {
        $model = EscortModel::find($id);
        if (!$model) {
            return null;
        }
        // Chuyển đổi từ model sang entity
        return new Escort(
            $model->id,
            $model->name,
            $model->description,
            $model->image,
            $model->status,
            $model->created_by,
            $model->updated_by
        );
    }

    // Lưu (tạo hoặc cập nhật) Escort
    public function save(Escort $escort): Escort
    {
        if ($escort->getId()) {
            // Cập nhật
            $model = EscortModel::find($escort->getId());
            if (!$model) {
                // Nếu không tìm thấy model, tạo mới
                $model = new EscortModel();
            }
        } else {
            // Tạo mới
            $model = new EscortModel();
        }

        // Gán dữ liệu từ entity vào model
        $model->name = $escort->getName();
        $model->description = $escort->getDescription();
        $model->image = $escort->getImage();
        $model->status = $escort->getStatus();
        $model->created_by = $escort->getCreatedBy();
        $model->updated_by = $escort->getUpdatedBy();

        $model->save();

        // Cập nhật ID (nếu cần) và trả về entity
        $escort->setId($model->id);
        return $escort;
    }

    // Xóa Escort
    public function delete(Escort $escort): void
    {
        $model = EscortModel::find($escort->getId());
        if ($model) {
            $model->delete();
        }
    }

    // Phân trang
    public function paginate(int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;
        $models = EscortModel::skip($offset)->take($perPage)->get();
        $result = [];
        foreach ($models as $model) {
            $result[] = new Escort(
                $model->id,
                $model->name,
                $model->description,
                $model->image,
                $model->status,
                $model->created_by,
                $model->updated_by
            );
        }
        return $result;
    }
}
