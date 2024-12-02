<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:House,Apartment',
            'address' => 'required|string',
            'size' => 'required|integer',
            'bedrooms' => 'required|integer',
            'price' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $property = Property::create($validated);
        return response()->json($property, 201);
    }

    public function index(Request $request)
    {
        $properties = Property::query();

        if ($request->has('type')) {
            $properties->where('type', $request->type);
        }
        if ($request->has('bedrooms')) {
            $properties->where('bedrooms', $request->bedrooms);
        }
        if ($request->has('price')) {
            $properties->where('price', '<=', $request->price);
        }

        return response()->json($properties->get());
    }

    public function findNearby(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric',
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];
        $radius = $validated['radius'];

        $properties = Property::selectRaw("
            id, type, address, size, bedrooms, price, latitude, longitude,
            (6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->get();

        return response()->json($properties);
    }
}

