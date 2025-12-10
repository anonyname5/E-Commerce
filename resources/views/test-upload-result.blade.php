<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Upload Results</span>
                        <a href="{{ route('test.upload.form') }}" class="btn btn-sm btn-primary">Try Again</a>
                    </div>
                    <div class="card-body">
                        <h5>{{ count($files) }} file(s) uploaded successfully</h5>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>MIME Type</th>
                                        <th>Valid</th>
                                        <th>Stored At</th>
                                        <th>Preview</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($files as $index => $file)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $file['name'] }}</td>
                                        <td>{{ round($file['size'] / 1024, 2) }} KB</td>
                                        <td>{{ $file['mime'] }}</td>
                                        <td>{{ $file['valid'] ? 'Yes' : 'No' }}</td>
                                        <td>{{ $file['path'] }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $file['path']) }}" alt="Uploaded image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Raw Data:</h5>
                            <pre class="bg-light p-3 rounded">{{ json_encode($files, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 