<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }
    public function upload()
    {
        if (request()->has('mycsv')) {
            $data = file(request()->mycsv);

            // note: Chungking File
            $chunks = array_chunk($data, 1000);

            $header = [];
            $batch = Bus::batch([])->dispatch();

            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                if ($key === 0) {
                    $header = $data[0];
                    // todo: Remove first array, header
                    unset($data[0]);
                }

                // todo: Try to make failed job
                // if ($key == 2) {
                //     $header = [];
                // }

                // todo: Running the job
                // SalesCsvProcess::dispatch($data, $header);
                // note: Better langsung di batching
                $batch->add(new SalesCsvProcess($data, $header));
            }
            return $batch;
        }
        return 'Please upload file';
    }

    public function batch()
    {
        $batchId = request('id');
        return Bus::findBatch($batchId);
    }
}
