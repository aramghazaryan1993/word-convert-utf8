<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\Request;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class WordConvertController extends Controller
{
    public function index()
    {
        $directory = storage_path('app/word-convert/');
        $files = scandir($directory, SCANDIR_SORT_DESCENDING);

        foreach ($files as $key => $value) {
            if ($value === ".." || $value === ".") {
                unset($files[$key]);
            }
        }

        $fileList = array_values($files);

        return view('index',compact('fileList'));
    }

    public function upload(UploadRequest $request)
    {
        Settings::setDefaultFontName('Arial LatArm');

        if ($request->hasFile('file')) {
            $wordFile = $request->file('file');

            if ($wordFile->getClientOriginalExtension() === 'docx') {
                $phpWord = IOFactory::load($wordFile);


                // Get all sections in the document
                $sections = $phpWord->getSections();

// Iterate through each section to remove table borders
                foreach ($sections as $section) {
                    $elements = $section->getElements();

                    // Iterate through elements in the section
                    foreach ($elements as $element) {
                        // Check if the element is a table
                        if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                            $rows = $element->getRows();

                            // Iterate through rows
                            foreach ($rows as $row) {
                                $cells = $row->getCells();

                                // Iterate through cells to remove borders
                                foreach ($cells as $cell) {
                                    // Access cell properties and remove the border
                                    $cellStyle = $cell->getStyle();
                                    $cellStyle->setBorderTopSize(0);
                                    $cellStyle->setBorderRightSize(0);
                                    $cellStyle->setBorderBottomSize(0);
                                    $cellStyle->setBorderLeftSize(0);
                                }
                            }
                        }
                    }
                }

                $wordFile = Str::random(10) . '.docx';
                $newFilePath = storage_path('app/word-convert/New-'.$wordFile);
                $phpWord->save($newFilePath);

                return redirect()->back();
            } else {
                return "Please upload a .docx file.";
            }
        } else {
            return "No file was uploaded.";
        }
    }

    public function download(request $request)
    {
        $file =  storage_path('app/word-convert/').$request->file_name;
        if (file_exists($file)) {
            return Response::download($file);
        }
    }

    public function delete(request $request)
    {
        unlink(storage_path('app/word-convert/') . $request->file_name);
        return redirect()->back();
    }
}
