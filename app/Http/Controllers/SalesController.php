<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }
    public function store()
    {
        if (request()->has('mycsv')) {
            $data = array_map('str_getcsv', file(request()->mycsv));
            $header = $data[0];
            return $header;
            unset($data[0]);
            foreach ($data as $item) {
                // 
            }
        }
        return 'please upload file';
    }
}
