<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function create(Request $request)
    {
        return "create";
    }

    public function read(Request $request)
    {
        return "read";
    }

    public function update(Request $request)
    {
        return "update";
    }

    public function delete(Request $request)
    {
        return "delete";
    }
}
