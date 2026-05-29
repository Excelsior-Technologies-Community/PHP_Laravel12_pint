<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PintController extends Controller
{
    // Display Pint dashboard with code samples
    public function dashboard()
    {
        $badCodeSample = $this->getBadCodeSample();
        $cleanCodeSample = $this->getCleanCodeSample();
        
        return view('pint.dashboard', compact('badCodeSample', 'cleanCodeSample'));
    }
    
    // Check code quality with detailed report
    public function checkCode()
    {
        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }
        
        $output = [];
        $returnCode = 0;
        exec("\"$path\" --test -v 2>&1", $output, $returnCode);
        
        $outputString = implode("\n", $output);
        $hasIssues = $returnCode !== 0;
        
        // Parse output for detailed report
        $filesWithIssues = $this->parsePintOutput($outputString);
        
        return response()->json([
            'success' => !$hasIssues,
            'message' => $hasIssues ? '⚠️ Code style issues found' : '✅ Code is PSR-12 compliant!',
            'output' => $outputString,
            'files_with_issues' => $filesWithIssues,
            'return_code' => $returnCode
        ]);
    }
    
    // Auto-fix code
    public function fixCode(Request $request)
    {
        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }
        
        $specificFile = $request->get('file');
        $command = "\"$path\"";
        
        if ($specificFile && file_exists(base_path($specificFile))) {
            $command .= " " . escapeshellarg(base_path($specificFile));
        }
        
        $output = [];
        exec("$command 2>&1", $output);
        
        $outputString = implode("\n", $output);
        $fixed = str_contains($outputString, '✓') || str_contains($outputString, 'PASS');
        
        return response()->json([
            'success' => $fixed,
            'message' => $fixed ? '🔧 Code formatted successfully!' : 'No changes needed or error occurred',
            'output' => $outputString,
            'files_fixed' => $this->countFixedFiles($outputString)
        ]);
    }
    
    // Create temporary bad code file for testing
    public function createTestFile()
    {
        $testFilePath = app_path('Http/Controllers/TestBadCodeController.php');
        
        $badCode = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestBadCodeController extends Controller{
public function index(){
return "This   is   badly    formatted   code";
}
public function store(Request $request){
$data=$request->all();
return response()->json(['status'=>'ok']);
}
}
PHP;
        
        File::put($testFilePath, $badCode);
        
        return response()->json([
            'success' => true,
            'message' => 'Test file created with bad formatting',
            'file' => 'app/Http/Controllers/TestBadCodeController.php'
        ]);
    }
    
    // Delete test file
    public function deleteTestFile()
    {
        $testFilePath = app_path('Http/Controllers/TestBadCodeController.php');
        
        if (File::exists($testFilePath)) {
            File::delete($testFilePath);
            return response()->json([
                'success' => true,
                'message' => 'Test file deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Test file not found'
        ]);
    }
    
    // Get code statistics
    public function getStats()
    {
        $phpFiles = $this->getAllPhpFiles();
        $totalFiles = count($phpFiles);
        $totalLines = 0;
        $totalSize = 0;
        
        foreach ($phpFiles as $file) {
            $content = File::get($file);
            $totalLines += substr_count($content, "\n");
            $totalSize += File::size($file);
        }
        
        // Check current code quality
        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }
        
        exec("\"$path\" --test 2>&1", $output, $returnCode);
        
        return response()->json([
            'total_php_files' => $totalFiles,
            'total_lines_of_code' => $totalLines,
            'total_size_kb' => round($totalSize / 1024, 2),
            'code_quality_status' => $returnCode === 0 ? 'clean' : 'needs_formatting',
            'php_version' => phpversion(),
            'laravel_version' => app()->version()
        ]);
    }
    
    private function getBadCodeSample()
    {
        return <<<'PHP'
public function test(){
return "Hello Laravel Pint";
}
PHP;
    }
    
    private function getCleanCodeSample()
    {
        return <<<'PHP'
public function test()
{
    return 'Hello Laravel Pint';
}
PHP;
    }
    
    private function parsePintOutput($output)
    {
        preg_match_all('/\s+([✗✓])\s+(app\/.*\.php)/', $output, $matches);
        $files = [];
        
        foreach ($matches[2] as $index => $file) {
            $files[] = [
                'file' => $file,
                'status' => $matches[1][$index] === '✓' ? 'fixed' : 'issue'
            ];
        }
        
        return $files;
    }
    
    private function countFixedFiles($output)
    {
        preg_match_all('/✓\s+(app\/.*\.php)/', $output, $matches);
        return count($matches[1]);
    }
    
    private function getAllPhpFiles()
    {
        $directories = ['app', 'routes', 'database', 'tests'];
        $files = [];
        
        foreach ($directories as $directory) {
            $path = base_path($directory);
            if (is_dir($path)) {
                $files = array_merge($files, glob("$path/**/*.php"));
            }
        }
        
        return $files;
    }
}