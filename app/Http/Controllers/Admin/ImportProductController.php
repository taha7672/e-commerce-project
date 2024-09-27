<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ImportProductController extends Controller
{

    public function __construct() {
        $this->middleware('permission:products,admin');
    }
    
    public function index(Request $request) {
        return view('admin.products.import', []);
    }

    public function upload(Request $request) {
        $request->validate([
            "filepond" => "required|file",
        ]);

        $path = $request->file('filepond')->store('temps/import');

        HeadingRowFormatter::default('none');
        $headerData = (new HeadingRowImport)->toArray( storage_path("app/{$path}") );

        return response()->json([
            "status" => "success",
            "data" => [
                "path" => $path,
                "headers" => $headerData[0][0],
                "columns" => config("business.imports.products.columns"),
            ],
        ]);
    }

    public function import(Request $request) {
        $request->validate([
            "path" => "required",
            "data" => "required|array",
        ]);

        $path = storage_path("app/{$request->path}");

        if ( !file_exists($path) ) {
            return response()->json([
                "status" => "error",
                "error" => "file not found",
            ], 404);
        }

        Excel::import(new ProductsImport($request->data), $path);
        @unlink($path);

        return response()->json([ "status" => "success" ]);
    }

}
