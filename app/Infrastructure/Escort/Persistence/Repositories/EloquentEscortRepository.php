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
        return $model ? $this->toEntity($model) : null;
    }

    public function save(EscortEntity $escort): EscortEntity
    {
        if ($escort->getId()) {
            $model = EscortModel::find($escort->getId());
            if (!$model) {
                $model = new EscortModel();
            }
        } else {
            $model = new EscortModel();
        }

        $this->fillModelFromEntity($model, $escort)->save();

        $escort->setId($model->id);
        $escort->setCreatedAt($model->created_at);
        $escort->setUpdatedAt($model->updated_at);

        return $escort;
    }

    public function delete(EscortEntity $escort): void
    {
        if ($model = EscortModel::find($escort->getId())) {
            $model->delete();
        }
    }

    public function paginate(int $perPage, int $page): array
    {
        return EscortModel::skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->all();
    }

    private function toEntity(EscortModel $model): EscortEntity
    {
        return new EscortEntity(
            $model->id,
            $model->name,
            $model->description,
            $model->image,
            $model->status,
            $model->created_by,
            $model->updated_by,
            $model->created_at,
            $model->updated_at
        );
    }

    private function fillModelFromEntity(EscortModel $model, EscortEntity $escort): EscortModel
    {
        $model->name = $escort->getName();
        $model->description = $escort->getDescription();
        $model->image = $escort->getImage();
        $model->status = $escort->getStatus();
        $model->created_by = $escort->getCreatedBy();
        $model->updated_by = $escort->getUpdatedBy();
        $model->created_at = $escort->getCreatedAt();
        $model->updated_at = $escort->getUpdatedAt();
        return $model;
    }
}
