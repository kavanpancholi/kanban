<?php

namespace App\Http\Services;

use App\Http\Resources\ColumnResource;
use App\Models\Column;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Str;

class ColumnService
{
    /**
     * @return AnonymousResourceCollection
     */
    public function list(): AnonymousResourceCollection
    {
        $columns = Column::all();

        return ColumnResource::collection($columns);
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $column = Column::find($data['column_id']);

        $card = [
            'id' => Str::uuid(),
            'title' => $data['title'],
            'description' => $data['description'],
        ];

        $column->cards = collect($column->cards)
            ->push($card)->values();

        $column->save();

        return $card;
    }

    /**
     * @param string $cardId
     * @param array $data
     * @return void
     */
    public function reorder(string $cardId, array $data): void
    {
        $oldColumnWhereCardWasSaved = Column::where('cards', 'LIKE', '%'.$cardId.'%')->first();

        $newColumnWhereCardIsSaved = Column::find($data['column_id']);
        $customOrder = $data['orders'];

        /**
         * If old column does not match with the new column, that means card has been moved to another column
         * So in this case, we want to get the card details, then remove card from old column,
         * Then add that card to new column, and finally sort by the custom ordering from request
         */
        if ($oldColumnWhereCardWasSaved->id !== $newColumnWhereCardIsSaved->id) {
            // Find the Card details from lastly saved Column
            $cardDetails = collect($oldColumnWhereCardWasSaved->cards)->first(function ($card) use ($cardId) {
                return $card['id'] === $cardId;
            });

            // Delete card from old column
            $this->delete($cardId, $oldColumnWhereCardWasSaved);

            // Add card in new column & sort by custom order
            $newColumnCards = collect($newColumnWhereCardIsSaved->cards)
                ->push($cardDetails)
                ->sortBy(function($card) use ($customOrder) {
                    return array_search($card['id'], $customOrder);
                })
                ->values();

        } else {
            /**
             * If old column matches with the new column, that means only order has changed
             * So we just want to sort the cards by custom ordering from request and save it
             */

            // Add card in column
            $newColumnCards = collect($newColumnWhereCardIsSaved->cards)
                ->sortBy(function($card) use ($customOrder) {
                    return array_search($card['id'], $customOrder);
                })
                ->values();

        }
        $newColumnWhereCardIsSaved->cards = $newColumnCards;
        $newColumnWhereCardIsSaved->save();
    }

    /**
     * @param Column $column
     * @param $data
     * @return ColumnResource
     */
    public function update(Column $column, $data): ColumnResource
    {
        $column->title = $data['title'];
        $column->save();

        return ColumnResource::make($column);
    }

    /**
     * @param string $cardId
     * @param Column|null $column
     * @return void
     */
    public function delete(string $cardId, Column $column = null)
    {
        if (!$column) {
            $column = Column::where('cards', 'LIKE', '%'.$cardId.'%')->first();
        }

        // Remove card from old column
        $oldColumnCards = collect($column->cards)->filter(function ($card) use ($cardId) {
            return $card['id'] !== $cardId;
        });
        $column->cards = $oldColumnCards;
        $column->save();
    }
}
