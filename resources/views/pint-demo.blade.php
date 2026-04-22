<!DOCTYPE html>
<html>

<head>
    <title>Laravel Pint Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <h2 class="text-center mb-4">🚀 Laravel Pint Code Formatter Demo</h2>

        <div class="text-center mb-4">
            <a href="/run-pint" class="btn btn-danger me-2">🔍 Check Code</a>
            <a href="/fix-pint" class="btn btn-success">🔧 Fix Code</a>
        </div>

        <div class="card p-4 mb-4">
            <h4>❌ Before Pint (Bad Code)</h4>
            <pre>
public function test(){
return "Hello Laravel Pint";
}
        </pre>
        </div>

        <div class="card p-4">
            <h4>✅ After Pint (Clean Code)</h4>
            <pre>
public function test()
{
    return 'Hello Laravel Pint';
}
        </pre>
        </div>

    </div>

</body>

</html>