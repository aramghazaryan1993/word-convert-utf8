<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Word Convert</title>
        <link rel="stylesheet" href="{{asset('assets/style.css')}}"/>
    </head>
    <body >
    <div class="centered-form">
        <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".docx">
            <button type="submit" class="pointer">Upload</button>
        </form>
    </div>

    @error('wordFile')
    <div class="error"  style="text-align: center">{{ $message }}</div>
    @enderror

    <h1 id="title">File List</h1>

    <table>
        <tr>
            <th>File Name</th>
            <th>Download</th>
            <th>Delete</th>
        </tr>
        @foreach($fileList as $key => $value)
        <tr>
                <td>{{$value}}</td>
                <td>
                    <form method="POST" action="{{route('download')}}">
                        @csrf
                        <input type="hidden" name="file_name" value="{{$value}}">
                        <input type="submit"  class="pointer" value="Download">
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{route('delete')}}">
                        @csrf
                        <input type="hidden" name="file_name" value="{{$value}}">
                        <input type="submit" class="pointer"  value="Delete">
                    </form>
                </td>
        </tr>
        @endforeach
    </table>
    </body>
</html>
