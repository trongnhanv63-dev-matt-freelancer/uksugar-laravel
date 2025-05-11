<?php

namespace App\Presentation\Escort\Controllers;


use App\Application\Escort\ActionHandlers\ListEscortActionHandler;
use App\Application\Escort\ActionHandlers\ShowEscortActionHandler;
use App\Application\Escort\ActionHandlers\CreateEscortActionHandler;
use App\Application\Escort\ActionHandlers\DeleteEscortActionHandler;
use App\Application\Escort\ActionHandlers\UpdateEscortActionHandler;
use App\Application\Escort\Actions\CreateEscortAction;
use App\Application\Escort\Actions\DeleteEscortAction;
use App\Application\Escort\Actions\ListEscortAction;
use App\Application\Escort\Actions\ShowEscortAction;
use App\Application\Escort\Actions\UpdateEscortAction;
use App\Application\Escort\DTOs\EscortData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EscortApiController
{
    /**
     * Sử dụng Dependency Injection để nhận các Command/Query Handler.
     */
    public function __construct(
        protected ListEscortActionHandler $listActionHandler,
        protected ShowEscortActionHandler $showActionHandler,
        protected CreateEscortActionHandler $createActionHandler,
        protected UpdateEscortActionHandler $updateActionHandler,
        protected DeleteEscortActionHandler $deleteActionHandler
    ) {}

    /**
     * GET /api/escorts
     */
    public function index(): JsonResponse
    {
        $escorts = $this->listActionHandler->handle(new ListEscortAction());
        return response()->json($escorts, 200);
    }

    /**
     * GET /api/escorts/{id}
     */
    public function show(int $id): JsonResponse
    {
        $escort = $this->showActionHandler->handle(new ShowEscortAction($id));
        return response()->json($escort, 200);
    }

    /**
     * POST /api/escorts
     */
    public function store(Request $request): JsonResponse
    {
        $action = new CreateEscortAction(new EscortData(
            $request->input('name'),
            $request->input('description')
        ));
        $createdEscort = $this->createActionHandler->handle($action);
        return response()->json($createdEscort, 201);
    }

    /**
     * PUT/PATCH /api/escorts/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $command = new UpdateEscortAction(
            $id,
            new EscortData(
                $request->input('name'),
                $request->input('description')
            )
        );
        $updatedEscort = $this->updateActionHandler->handle($command);
        return response()->json($updatedEscort, 200);
    }

    /**
     * DELETE /api/escorts/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $this->deleteActionHandler->handle(new DeleteEscortAction($id));
        return response()->json(null, 204);
    }
}
