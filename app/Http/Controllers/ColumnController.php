<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardOrderRequest;
use App\Http\Requests\CardRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Resources\ColumnResource;
use App\Http\Services\ColumnService;
use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ColumnController extends Controller
{
    /**
     * @var ColumnService
     */
    protected ColumnService $columnService;

    /**
     * @param ColumnService $columnService
     */
    public function __construct(ColumnService $columnService)
    {
        $this->columnService = $columnService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return $this->columnService->list();
    }

    /**
     * @param CardRequest $request
     * @return ColumnResource
     */
    public function store(CardRequest $request)
    {
        return $this->columnService->create($request->validated());
    }

    public function reorder(CardOrderRequest $request, string $cardId)
    {
        $this->columnService->reorder($cardId, $request->validated());
    }

    /**
     * @param Column $column
     * @return AnonymousResourceCollection
     */
    public function show(Column $column)
    {
        return $this->columnService->show($column);
    }

    /**
     * @param DeleteCardRequest $request
     * @param Column $column
     * @return JsonResponse
     */
    public function destroyCard(DeleteCardRequest $request, Column $column)
    {
        $this->columnService->deleteCard($request->get('card_id'), $column);

        return response()->json([
            'message' => 'Card has been removed successfully'
        ]);
    }

    /**
     * @param Column $column
     * @return JsonResponse
     */
    public function destroy(Column $column)
    {
        $this->columnService->delete($column);

        return response()->json([
            'message' => 'Column has been removed successfully'
        ]);
    }
}
