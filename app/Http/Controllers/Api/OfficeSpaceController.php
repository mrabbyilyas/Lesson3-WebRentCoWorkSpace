<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OfficeSpaceResource;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfficeSpaceController extends Controller
{
    //
    public function index()
    {
        $officeSpaces = OfficeSpace::with('city')->get();
        return OfficeSpaceResource::collection($officeSpaces);
    }

    public function show(OfficeSpace $officeSpace)
    {
        $officeSpace->load(['city', 'images', 'benefits']);
        return new OfficeSpaceResource($officeSpace);
    }
}
