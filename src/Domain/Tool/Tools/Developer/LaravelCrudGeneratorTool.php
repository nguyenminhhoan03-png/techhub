<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Illuminate\Support\Str;

class LaravelCrudGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'laravel-crud-generator';
    }

    public function name(): string
    {
        return 'Laravel RESTful CRUD Scaffolding Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate production-ready Laravel 11/12 CRUD scaffolding: Migration, Model, Form Requests, API Controller, and Resource from field definitions.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'model_name' => ['required', 'string', 'max:60'],
            'fields' => ['required', 'string'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawModelName = trim((string) ($input['model_name'] ?? ''));
        $rawFields = trim((string) ($input['fields'] ?? ''));

        if (empty($rawModelName) || empty($rawFields)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Model name and field definitions are required.', $executionTimeMs);
        }

        $modelName = Str::studly(Str::singular($rawModelName));
        $pluralName = Str::plural(Str::snake($modelName));
        $varName = Str::camel($modelName);
        $pluralVar = Str::camel(Str::plural($modelName));

        // Normalize types with comma like decimal:10,2 -> decimal:10|2
        $normalizedFields = (string) preg_replace('/(\w+):(\d+),(\d+)/', '$1:$2|$3', $rawFields);
        $fieldItems = explode(',', $normalizedFields);
        $parsedFields = [];

        foreach ($fieldItems as $item) {
            $item = trim($item);
            if (empty($item)) {
                continue;
            }

            $parts = explode(':', $item, 2);
            $fieldName = (string) trim($parts[0]);
            $fieldType = isset($parts[1]) ? trim(str_replace('|', ',', $parts[1])) : 'string';

            if ( ! empty($fieldName)) {
                $parsedFields[$fieldName] = $fieldType;
            }
        }

        if (empty($parsedFields)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Could not parse any valid fields from input.', $executionTimeMs);
        }

        // 1. Migration
        $migrationCols = [];
        foreach ($parsedFields as $fName => $fType) {
            $fNameStr = (string) $fName;
            if ('foreignId' === $fType || str_ends_with($fNameStr, '_id')) {
                $related = Str::plural(str_replace('_id', '', $fNameStr));
                $migrationCols[] = "            \$table->foreignId('{$fNameStr}')->constrained('{$related}')->cascadeOnDelete();";
            } elseif ('string' === $fType) {
                $migrationCols[] = "            \$table->string('{$fNameStr}');";
            } elseif ('text' === $fType) {
                $migrationCols[] = "            \$table->text('{$fNameStr}');";
            } elseif ('boolean' === $fType || 'bool' === $fType) {
                $migrationCols[] = "            \$table->boolean('{$fNameStr}')->default(false);";
            } elseif (str_starts_with($fType, 'decimal')) {
                $migrationCols[] = "            \$table->decimal('{$fNameStr}', 10, 2);";
            } elseif ('integer' === $fType || 'int' === $fType) {
                $migrationCols[] = "            \$table->integer('{$fNameStr}');";
            } elseif ('json' === $fType) {
                $migrationCols[] = "            \$table->json('{$fNameStr}')->nullable();";
            } else {
                $migrationCols[] = "            \$table->string('{$fNameStr}');";
            }
        }

        $migrationCode = <<<PHP
// ── 1. Migration: create_{$pluralName}_table.php ──
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$pluralName}', function (Blueprint \$table): void {
            \$table->id();
PHP . "\n" . implode("\n", $migrationCols) . "\n" . <<<PHP
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$pluralName}');
    }
};
PHP;

        // 2. Model
        $fillableList = implode(",\n        ", array_map(fn($k) => "'{$k}'", array_keys($parsedFields)));
        $modelCode = <<<PHP
// ── 2. Model: app/Models/{$modelName}.php ──
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$table = '{$pluralName}';

    protected \$fillable = [
        {$fillableList},
    ];
}
PHP;

        // 3. Store Request
        $validationRules = [];
        foreach ($parsedFields as $fName => $fType) {
            $rule = match ($fType) {
                'integer', 'int' => "['required', 'integer']",
                'boolean', 'bool' => "['required', 'boolean']",
                'text' => "['required', 'string']",
                'json' => "['nullable', 'array']",
                default => "['required', 'string', 'max:255']",
            };
            if ('foreignId' === $fType || str_ends_with($fName, '_id')) {
                $related = Str::plural(str_replace('_id', '', $fName));
                $rule = "['required', 'exists:{$related},id']";
            }
            $validationRules[] = "            '{$fName}' => {$rule},";
        }

        $requestCode = <<<PHP
// ── 3. Store Request: app/Http/Requests/{$modelName}StoreRequest.php ──
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$modelName}StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
PHP . "\n" . implode("\n", $validationRules) . "\n" . <<<PHP
        ];
    }
}
PHP;

        // 4. Controller
        $controllerCode = <<<PHP
// ── 4. Controller: app/Http/Controllers/Api/{$modelName}Controller.php ──
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\\{$modelName}StoreRequest;
use App\Http\Resources\\{$modelName}Resource;
use App\Models\\{$modelName};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class {$modelName}Controller extends Controller
{
    public function index(): JsonResponse
    {
        \${$pluralVar} = {$modelName}::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => {$modelName}Resource::collection(\${$pluralVar}),
            'meta' => [
                'current_page' => \${$pluralVar}->currentPage(),
                'total' => \${$pluralVar}->total(),
            ],
        ]);
    }

    public function store({$modelName}StoreRequest \$request): JsonResponse
    {
        \${$varName} = {$modelName}::create(\$request->validated());

        return response()->json([
            'success' => true,
            'message' => '{$modelName} created successfully.',
            'data' => new {$modelName}Resource(\${$varName}),
        ], 201);
    }

    public function show({$modelName} \${$varName}): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new {$modelName}Resource(\${$varName}),
        ]);
    }

    public function update(Request \$request, {$modelName} \${$varName}): JsonResponse
    {
        \${$varName}->update(\$request->all());

        return response()->json([
            'success' => true,
            'message' => '{$modelName} updated successfully.',
            'data' => new {$modelName}Resource(\${$varName}),
        ]);
    }

    public function destroy({$modelName} \${$varName}): JsonResponse
    {
        \${$varName}->delete();

        return response()->json([
            'success' => true,
            'message' => '{$modelName} deleted successfully.',
        ], 200);
    }
}
PHP;

        // 5. Resource
        $resourceFields = [];
        foreach (array_keys($parsedFields) as $fName) {
            $resourceFields[] = "            '{$fName}' => \$this->{$fName},";
        }
        $resourceCode = <<<PHP
// ── 5. Resource: app/Http/Resources/{$modelName}Resource.php ──
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$modelName}Resource extends JsonResource
{
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
PHP . "\n" . implode("\n", $resourceFields) . "\n" . <<<PHP
            'created_at' => \$this->created_at?->toIso8601String(),
            'updated_at' => \$this->updated_at?->toIso8601String(),
        ];
    }
}
PHP;

        $routesSnippet = "// ── 6. Routes: routes/api.php ──\nRoute::apiResource('{$pluralName}', App\Http\Controllers\Api\\{$modelName}Controller::class);";

        $fullBundle = implode("\n\n" . str_repeat('─', 70) . "\n\n", [
            $migrationCode,
            $modelCode,
            $requestCode,
            $controllerCode,
            $resourceCode,
            $routesSnippet,
        ]);

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $fullBundle,
            'model_name' => $modelName,
            'table_name' => $pluralName,
            'fields_count' => count($parsedFields),
            'migration' => $migrationCode,
            'model' => $modelCode,
            'store_request' => $requestCode,
            'controller' => $controllerCode,
            'resource' => $resourceCode,
            'routes' => $routesSnippet,
        ], executionTimeMs: $executionTimeMs);
    }
}
