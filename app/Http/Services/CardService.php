<?php

namespace App\Http\Services;

use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 *
 */
class CardService
{
    /**
     * @return AnonymousResourceCollection
     */
    public function list(): AnonymousResourceCollection
    {
        $cards = Card::all();

        return CardResource::collection($cards);
    }

    /**
     * @param Card $card
     * @return AnonymousResourceCollection
     */
    public function show(Card $card): AnonymousResourceCollection
    {
        return CardResource::make($card);
    }

    /**
     * @param $data
     * @return CardResource
     */
    public function create($data): CardResource
    {
        $card = new Card();
        $card->fill($data);
        $card->save();

        return CardResource::make($card);
    }

    /**
     * @param Card $card
     * @param $data
     * @return CardResource
     */
    public function update(Card $card, $data): CardResource
    {
        $card->title = $data['title'];
        $card->description = $data['description'];
        $card->order = $data['order'];
        $card->column_id = $data['column_id'];
        $card->save();

        return CardResource::make($card);
    }

    /**
     * @param Card $card
     * @return true
     */
    public function delete(Card $card): bool
    {
        $card->delete();

        return true;
    }
}
