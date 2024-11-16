<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RestaurantsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function import()
    {
        return view('import');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' =>
            'required|file|mimes:xlsx,csv',
        ]);

        Excel::import(new RestaurantsImport, $request->file('file'));

        return redirect()->back();
    }

    public function store($filePath)
    {
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle,1000,'r')) !== false) {
                $imageUrl = $data[4];

                $imageContents = file_get_contents($imageUrl);

                $imageName = basename($imageUrl);

                Storage::disk('public')->put("images/{$imageName}",$imageContents);
            }
            fclose($handle);
        }
    }
}
