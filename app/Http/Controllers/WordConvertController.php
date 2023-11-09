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

                $sections = $phpWord->getSections();
                foreach ($sections as $section) {
                    $elements = $section->getElements();
                    foreach ($elements as $element) {
                        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            $element->setDefaultFontName('Arial LatArm');
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
