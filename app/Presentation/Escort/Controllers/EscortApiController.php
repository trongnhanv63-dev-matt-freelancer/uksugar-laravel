<?php

namespace App\Presentation\Escort\Controllers;

use App\Application\Escort\Commands\CreateEscortCommand;
use App\Application\Escort\Commands\UpdateEscortCommand;
use App\Application\Escort\Commands\DeleteEscortCommand;
use App\Application\Escort\Queries\ListEscortsQuery;
use App\Application\Escort\Queries\GetEscortByIdQuery;
use App\Application\Escort\CommandHandlers\CreateEscortCommandHandler;
use App\Application\Escort\CommandHandlers\UpdateEscortCommandHandler;
use App\Application\Escort\CommandHandlers\DeleteEscortCommandHandler;
use App\Application\Escort\DTOs\EscortData;
use App\Application\Escort\QueryHandlers\ListEscortsQueryHandler;
use App\Application\Escort\QueryHandlers\GetEscortByIdQueryHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EscortApiController
{
    /**
     * Sử dụng Dependency Injection để nhận các Command/Query Handler.
     */
    public function __construct(
        protected ListEscortsQueryHandler $listHandler,
        protected GetEscortByIdQueryHandler $showHandler,
        protected CreateEscortCommandHandler $createHandler,
        protected UpdateEscortCommandHandler $updateHandler,
        protected DeleteEscortCommandHandler $deleteHandler
    ) {}

    /**
     * GET /api/escorts
     * Trả về danh sách Escort.
     */
    public function index(): JsonResponse
    {
        // Gọi handler để lấy danh sách (có thể là ListEscortsQuery hoặc tương tự)
        $escorts = $this->listHandler->handle(new ListEscortsQuery());
        return response()->json($escorts, 200);
    }

    /**
     * GET /api/escorts/{id}
     * Trả về chi tiết Escort theo ID.
     */
    public function show(int $id): JsonResponse
    {
        $escort = $this->showHandler->handle(new GetEscortByIdQuery($id));
        return response()->json($escort, 200);
    }

    /**
     * POST /api/escorts
     * Tạo mới Escort.
     */
    public function store(Request $request): JsonResponse
    {
        // Tạo và dispatch CreateEscortCommand với dữ liệu từ request
        $command = new CreateEscortCommand(new EscortData(
            $request->input('name'),
            $request->input('description')
        ));
        $createdEscort = $this->createHandler->handle($command);
        return response()->json($createdEscort, 201);
    }

    /**
     * PUT/PATCH /api/escorts/{id}
     * Cập nhật Escort.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $command = new UpdateEscortCommand(
            $id,
            new EscortData(
                $request->input('name'),
                $request->input('description')
            )
        );
        $updatedEscort = $this->updateHandler->handle($command);
        return response()->json($updatedEscort, 200);
    }

    /**
     * DELETE /api/escorts/{id}
     * Xóa Escort.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deleteHandler->handle(new DeleteEscortCommand($id));
        return response()->json(null, 204);
    }
}
