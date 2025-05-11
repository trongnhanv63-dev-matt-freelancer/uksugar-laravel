<?php

namespace App\Infrastructure\Escort\Persistence\Repositories;

use App\Domain\Escort\Entities\EscortEntity;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;
use App\Infrastructure\Escort\Persistence\Eloquent\EscortModel;

class EloquentEscortRepository implements EscortRepositoryInterface
{
    public function findById(int $id): ?EscortEntity
    {
        $model = EscortModel::find($id);
        if (!$model) {
            return null;
        }
        return new EscortEntity(
            $model->id,
            $model->name,
            $model->description,
            $model->image,
            $model->status,
            $model->created_by,
            $model->updated_by
        );
    }

    public function save(EscortEntity $escort): EscortEntity
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

        $escort->setId($model->id);
        return $escort;
    }

    public function delete(EscortEntity $escort): void
    {
        $model = EscortModel::find($escort->getId());
        if ($model) {
            $model->delete();
        }
    }

    public function paginate(int $perPage, int $page): array
    {
        $offset = ($page - 1) * $perPage;
        $models = EscortModel::skip($offset)->take($perPage)->get();
        $result = [];
        foreach ($models as $model) {
            $result[] = new EscortEntity(
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
