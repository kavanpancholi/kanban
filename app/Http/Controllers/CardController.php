<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Http\Services\CardService;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CardController extends Controller
{
    /**
     * @var CardService
     */
    protected CardService $cardService;

    /**
     * @param CardService $cardService
     */
    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return $this->cardService->list();
    }

    /**
     * @param CardRequest $request
     * @return CardResource
     */
    public function store(CardRequest $request)
    {
        return $this->cardService->create($request->validated());
    }

    /**
     * @param Card $card
     * @return AnonymousResourceCollection
     */
    public function show(Card $card)
    {
        return $this->cardService->show($card);
    }

    /**
     * @param CardRequest $request
     * @param Card $card
     * @return CardResource
     */
    public function update(CardRequest $request, Card $card)
    {
        return $this->cardService->update($card, $request->validated());
    }

    /**
     * @param Card $card
     * @return JsonResponse
     */
    public function destroy(Card $card)
    {
        $this->cardService->delete($card);

        return response()->json([
            'message' => 'Card has been removed successfully'
        ]);
    }
}
