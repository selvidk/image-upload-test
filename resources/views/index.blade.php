<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image Upload Test | Selvi Dwi Kartika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        .container {
            max-width: auto;
        }

        .title {
            padding: 1.5em;
            text-align: center;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="title">Image Upload Test</h2>

        <form action="{{ url('/image/upload') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-auto mb-3">
                    <input type="file" class="form-control form-control-md @error('image') is-invalid @enderror"
                        name="image" id="image">
                    @error('image')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Upload</button>
                </div>
            </div>
        </form>
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="alert-message">
                <strong>{{ $message }}</strong>
            </div>
        </div>
        @endif
        <table class="table mt-3" style="counter-reset: rowNumber;">
            <thead>
                <th>Image</th>
                <th>Image Name</th>
                <th>Action</th>
            </thead>
            <tbody>
                @php
                $i = 1;
                @endphp
                @foreach ($images as $image)
                <tr>
                    <td><img src="{{ url('asset/images/'. $image->getFilename()) }}" width="100"></td>
                    <td>{{ explode('_', $image->getFilename())[1] }}</td>
                    <td style="width: 5px"><a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalDelete{{ $i++ }}">Update</a></td>
                </tr>
                <div class="modal" tabindex="-1" id="modalDelete{{ $i-1 }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Image Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/image/delete') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <p>Delete:</p>
                                    <p>{{ explode('_', $image->getFilename())[1] }}</p>
                                    <input type="hidden" name="image_del" id="image"
                                        value="{{ $image->getFilename() }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>