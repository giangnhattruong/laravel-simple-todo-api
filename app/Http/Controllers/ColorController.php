<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index() {
        $colors = Color::all();

        if ($colors->isEmpty()) {
            return response()->json(
                $data = ['message' => 'Colors not found'],
                $status = 404
            );
        }

        return ColorResource::collection($colors);
    }

    public function store(StoreColorRequest $request) {
        $color = Color::create([
            'name' => $request->validated()['name'],
        ]);

        return new ColorResource(Color::find($color->id));
    }

    public function show($id) {
        $color = Color::find($id);

        if ($color === null) {
            return response()->json(
                $data = ['message' => 'Color not found'],
                $status = 404
            );
        }

        return new ColorResource($color);
    }

    public function update(UpdateColorRequest $request, $id) {
        $color = Color::find($id);
        
        if ($color === null) {
            return response()->json(
                $data = ['message' => 'Color not found'],
                $status = 404
            );
        }
        
        $colorInput = $request->validated();
        $color->update([
            'name' => isset($colorInput['name']) ? $colorInput['name'] : $color->name,
        ]);

        return new ColorResource(Color::find($id));
    }

    public function destroy($id) {
        $color = Color::find($id);

        if ($color === null) {
            return response()->json(
                $data = ['message' => 'Color not found'],
                $status = 404
            );
        }

        $color->delete();

        return new ColorResource($color);
    }
}
