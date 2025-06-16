<?php

namespace App\Http\Controllers\Translation;

use App\Helpers\ApiJsonResponseHelper;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Http\Resources\TranslationResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class TranslationController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/translations",
     *     summary="Get list of translations",
     *     tags={"Translations"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by key, value, tag, or language code",
     *         required=false,
     *         @OA\Schema(type="string", example="welcome")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="translations", type="array", @OA\Items(ref="#/components/schemas/Translation")),
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer")
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return ApiJsonResponseHelper::successResponse([], "not Authenticated");
        }
        $query = Translation::query();
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%$search%")
                    ->orWhere('value', 'like', "%$search%")
                    ->orWhereJsonContains('tags', $search)
                    ->orWhereHas('language', function ($subQuery) use ($search) {
                        $subQuery->where('code', 'like', "%$search%");
                    });
            });
        }

        $translations = $query->with('language')->paginate(5);
        $data = [
            'current_page' => $translations->currentPage(),
            'last_page'    => $translations->lastPage(),
            'total'        => $translations->total(),
            'per_page'     => $translations->perPage(),
            'translations' => TranslationResource::collection($translations)
        ];
        return ApiJsonResponseHelper::successResponse($data, "");
    }
    /**
     * @OA\Post(
     *     path="/api/store-translations",
     *     summary="Add a new translation",
     *     description="Creates a new translation entry",
     *     tags={"Translations"},
     *     security={{"bearer_token":{}}},

     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key", "value", "language_id", "tags"},
     *             @OA\Property(property="key", type="string", example="welcome"),
     *             @OA\Property(property="value", type="string", example="Welcome"),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"web", "mobile"}
     *             )
     *         )
     *     ),

     *     @OA\Response(
     *         response=200,
     *         description="Translation added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Translation added successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Translation")
     *         )
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return ApiJsonResponseHelper::successResponse([], "not Authenticated");
        }
        $validator = Validator::make($request->all(), [
            'key'         => 'required|string',
            'value'       => 'required|string',
            'language_id' => 'required|exists:languages,id',
            'tags'        => 'required'
        ]);

        if ($validator->fails()) {
            return ApiJsonResponseHelper::apiValidationFailResponse($validator);
        }
        $data = $validator->validated();

        $translation = Translation::create([
            'key'         => $data['key'],
            'value'       => $data['value'],
            'language_id' => $data['language_id'],
            'tags'        => $data['tags'] ?? [],
        ]);

        return ApiJsonResponseHelper::successResponse(
            new TranslationResource($translation),
            'Translation added successfully'
        );
    }
}
