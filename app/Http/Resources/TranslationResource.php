<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Translation",
 *     type="object",
 *     title="Translation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="language_id", type="integer", example=2),
 *     @OA\Property(property="key", type="string", example="greeting"),
 *     @OA\Property(property="value", type="string", example="Hello"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="context", type="string", example="Used in homepage greeting"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class TranslationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value,
            'tags' => $this->tags,
            'language' => new LanguageResource($this->whenLoaded('language')),
        ];
    }
}
