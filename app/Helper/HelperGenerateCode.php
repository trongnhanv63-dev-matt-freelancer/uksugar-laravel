<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;
use Exception;

class HelperGenerateCode
{
    protected string $rootPath;
    protected array $actions = ['create', 'delete', 'list', 'show', 'update'];
    protected array $baseFolders = ['Application', 'Domain', 'Infrastructure', 'Presentation'];

    public function __construct()
    {
        $this->rootPath = app_path() . '/';
    }

    public function resetFolder(string $name): array
    {
        try {
            $name = $this->normalizeName($name);

            foreach ($this->baseFolders as $folder) {
                File::deleteDirectory($this->rootPath . $folder . '/' . $name);
            }
            $nameCal = $this->toCamelCase($name);
            /** Start route **/
            $this->updateRouter($name, $nameCal, true);
            /** End route **/

            return ['isError' => false, 'message' => 'Reset done'];
        } catch (Exception $e) {
            return ['isError' => true, 'message' => $e->getMessage()];
        }
    }

    public function generateCode(string $name): array
    {
        try {
            $name = $this->normalizeName($name);
            $this->resetFolder($name);

            $this->generateApplicationLayer($name);
            $this->generateDomainLayer($name);
            $this->generateInfrastructureLayer($name);
            $this->generatePresentationLayer($name);
            $nameCal = $this->toCamelCase($name);
            $this->updateRouter($name, $nameCal);
            $this->createMigrate($nameCal);

            return ['isError' => false, 'message' => 'Generate done'];
        } catch (Exception $e) {
            return ['isError' => true, 'message' => $e->getMessage()];
        }
    }

    protected function generateApplicationLayer(string $name): void
    {
        // ActionHandlers
        $path = $this->createFolder("Application/{$name}/ActionHandlers");
        foreach ($this->actions as $action) {
            $content = $this->buildContent('ActionHandler', $name, $action);
            $this->createFile($path, ucfirst($action) . $name . 'ActionHandler', '.php', $content);
        }

        // Actions
        $path = $this->createFolder("Application/{$name}/Actions");
        foreach ($this->actions as $action) {
            $content = $this->buildContent('Action', $name, $action);
            $this->createFile($path, ucfirst($action) . $name . 'Action', '.php', $content);
        }

        // DTOs
        $path = $this->createFolder("Application/{$name}/DTOs");
        $content = $this->buildContent('DTO', $name);
        $this->createFile($path, $name . 'Data', '.php', $content);
    }

    protected function generateDomainLayer(string $name): void
    {
        // Entities
        $path = $this->createFolder("Domain/{$name}/Entities");
        $content = $this->buildContent('Entity', $name);
        $this->createFile($path, $name . 'Entity', '.php', $content);

        // Repositories
        $path = $this->createFolder("Domain/{$name}/Repositories");
        $content = $this->buildContent('RepositoryInterface', $name);
        $this->createFile($path, $name . 'RepositoryInterface', '.php', $content);

        // ValueObjects
        $path = $this->createFolder("Domain/{$name}/ValueObjects");
        $content = $this->buildContent('ValueObject', $name);
        $this->createFile($path, $name . 'Status', '.php', $content);
    }

    protected function generateInfrastructureLayer(string $name): void
    {
        // Eloquent Model
        $path = $this->createFolder("Infrastructure/{$name}/Persistence/Eloquent");
        $content = $this->buildContent('Eloquent', $name);
        $this->createFile($path, $name . 'Model', '.php', $content);

        // Providers
        $path = $this->createFolder("Infrastructure/{$name}/Persistence/Providers");
        $content = $this->buildContent('Provider', $name);
        $this->createFile($path, $name . 'ServiceProvider', '.php', $content);

        // Repositories
        $path = $this->createFolder("Infrastructure/{$name}/Persistence/Repositories");
        $content = $this->buildContent('Repository', $name);
        $this->createFile($path, 'Eloquent' . $name . 'Repository', '.php', $content);
    }

    protected function generatePresentationLayer(string $name): void
    {
        // Controllers
        $path = $this->createFolder("Presentation/{$name}/Controllers");
        $content = $this->buildContent('Controller', $name);
        $this->createFile($path, $name . 'ApiController', '.php', $content);
    }

    protected function createFolder(string $path): string
    {
        $fullPath = $this->rootPath . $path;

        if (!File::isDirectory($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        return $fullPath . '/';
    }

    protected function createFile(string $path, string $name, string $extension = '.php', ?string $content = null): string
    {
        $pathFile = $path . $name . $extension;

        if (File::exists($pathFile)) {
            File::delete($pathFile);
        }

        File::put($pathFile, '<?php' . PHP_EOL . $content);

        return $pathFile;
    }

    protected function buildContent(string $type, string $name, ?string $action = null): string
    {
        $method = 'buildContent' . $type;
        if (!method_exists($this, $method)) {
            throw new Exception("Content type {$type} not supported");
        }

        return $this->$method($name, $action);
    }

    protected function buildContentActionHandler(string $name, string $action): string
    {
        $nameCamel = lcfirst($name);
        $actionName = ucfirst($action) . "{$name}Action";
        $repoName = "{$name}RepositoryInterface";
        $entityName = "{$name}Entity";

        $uses = [
            "use App\\Application\\{$name}\\Actions\\{$actionName};",
            "use App\\Domain\\{$name}\\Repositories\\{$repoName};",
        ];

        if (in_array($action, ['create', 'update'])) {
            $uses[] = "use App\\Domain\\{$name}\\Entities\\{$entityName};";
        }

        if ($action === 'show') {
            $uses[] = "use App\\Application\\{$name}\\DTOs\\{$name}Data;";
        }

        switch ($action) {
            case 'create':
                $handlerContent = $this->buildCreateHandlerContent($name, $nameCamel, $entityName);
                break;
            case 'delete':
                $handlerContent = $this->buildDeleteHandlerContent($name, $nameCamel);
                break;
            case 'list':
                $handlerContent = $this->buildListHandlerContent($name, $nameCamel);
                break;
            case 'show':
                $handlerContent = $this->buildShowHandlerContent($name, $nameCamel);
                break;
            case 'update':
                $handlerContent = $this->buildUpdateHandlerContent($name, $nameCamel, $entityName);
                break;
            default:
                throw new Exception("Action {$action} not supported");
        }

        return $this->buildClassTemplate(
            "App\\Application\\{$name}\\ActionHandlers",
            $actionName . 'Handler',
            $uses,
            $handlerContent
        );
    }

    protected function buildContentAction(string $name, string $action): string
    {
        $nameCamel = lcfirst($name);
        $actionName = ucfirst($action) . "{$name}Action";

        switch ($action) {
            case 'create':
                $properties = ["public {$name}Data \${$nameCamel}Data;"];
                break;
            case 'delete':
                $properties = ['public int $id;'];
                break;
            case 'list':
                $properties = ['public int $perPage;', 'public int $page;'];
                break;
            case 'show':
                $properties = ['public int $id;'];
                break;
            case 'update':
                $properties = ['public int $id;', "public {$name}Data \${$nameCamel}Data;"];
                break;
            default:
                $properties = [];
        }

        switch ($action) {
            case 'create':
                $constructorParams = ["{$name}Data \${$nameCamel}Data"];
                break;
            case 'delete':
                $constructorParams = ['int $id'];
                break;
            case 'list':
                $constructorParams = ['int $perPage = 10', 'int $page = 1'];
                break;
            case 'show':
                $constructorParams = ['int $id'];
                break;
            case 'update':
                $constructorParams = ['int $id', "{$name}Data \${$nameCamel}Data"];
                break;
            default:
                $constructorParams = [];
        }

        $constructorBody = array_map(
            fn($param) =>
            "\t\t\$this->" . substr(explode(' ', $param)[1], 1) . " = " . explode(' ', $param)[1] . ";",
            $constructorParams
        );

        $uses = $action === 'create' || $action === 'update'
            ? ["use App\\Application\\{$name}\\DTOs\\{$name}Data;"]
            : [];

        return $this->buildClassTemplate(
            "App\\Application\\{$name}\\Actions",
            $actionName,
            $uses,
            "\tpublic function __construct(" . PHP_EOL .
                "\t\t" . implode(',' . PHP_EOL . "\t\t", $constructorParams) . PHP_EOL .
                "\t) {" . PHP_EOL .
                implode(PHP_EOL, $constructorBody) . PHP_EOL .
                "\t}",
            [],
            'class',
            $properties
        );
    }

    protected function buildContentDTO(string $name): string
    {
        return $this->buildClassTemplate(
            "App\\Application\\{$name}\\DTOs",
            "{$name}Data",
            ["use DateTime;"],
            "\tpublic ?int \$created_by;" . PHP_EOL .
                "\tpublic ?int \$updated_by;" . PHP_EOL .
                "\tpublic ?DateTime \$created_at;" . PHP_EOL .
                "\tpublic ?DateTime \$updated_at;" . PHP_EOL . PHP_EOL .
                "\tpublic function __construct(" . PHP_EOL .
                "\t\t?int \$created_by = null," . PHP_EOL .
                "\t\t?int \$updated_by = null," . PHP_EOL .
                "\t\t?DateTime \$created_at = null," . PHP_EOL .
                "\t\t?DateTime \$updated_at = null" . PHP_EOL .
                "\t) {" . PHP_EOL .
                "\t\t\$this->created_at = \$created_at;" . PHP_EOL .
                "\t\t\$this->created_by = \$created_by;" . PHP_EOL .
                "\t\t\$this->updated_at = \$updated_at;" . PHP_EOL .
                "\t\t\$this->updated_by = \$updated_by;" . PHP_EOL .
                "\t}"
        );
    }

    protected function buildContentEntity(string $name): string
    {
        $columns = [
            'id' => ['type' => 'int', 'required' => false],
            'created_at' => ['type' => '\Datetime', 'required' => false],
            'created_by' => ['type' => 'int', 'required' => false],
            'updated_at' => ['type' => '\Datetime', 'required' => false],
            'updated_by' => ['type' => 'int', 'required' => false]
        ];

        $properties = [];
        $constructorParams = [];
        $constructorAssignments = [];
        $methods = [];

        foreach ($columns as $column => $config) {
            $camel = $this->toCamelCase($column);
            $uc = ucfirst($camel);
            $type = $config['type'];
            $required = $config['required'] ? '' : '?';

            $properties[] = "private \${$camel};";
            $constructorParams[] = "\t\t{$required}{$type} \${$camel}";
            $constructorAssignments[] = "\t\t\$this->{$camel} = \${$camel};";

            $methods[] = PHP_EOL . "\tpublic function get{$uc}(): {$required}{$type}" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\treturn \$this->{$camel};" . PHP_EOL .
                "\t}";

            $methods[] = PHP_EOL . "\tpublic function set{$uc}({$type} \${$camel})" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\t\$this->{$camel} = \${$camel};" . PHP_EOL .
                "\t}";
        }

        $methods[] = PHP_EOL . "\tpublic function toArray(): array" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\treturn get_object_vars(\$this);" . PHP_EOL .
            "\t}";

        return $this->buildClassTemplate(
            "App\\Domain\\{$name}\\Entities",
            "{$name}Entity",
            [],
            "\tpublic function __construct(" . PHP_EOL .
                implode(',' . PHP_EOL, $constructorParams) . PHP_EOL .
                "\t) {" . PHP_EOL .
                implode(PHP_EOL, $constructorAssignments) . PHP_EOL .
                "\t}" . PHP_EOL .
                implode(PHP_EOL, $methods),
            [],
            'class',
            $properties
        );
    }

    protected function buildContentRepositoryInterface(string $name): string
    {
        $nameCamel = lcfirst($name);

        return $this->buildClassTemplate(
            "App\\Domain\\{$name}\\Repositories",
            "{$name}RepositoryInterface",
            ["use App\\Domain\\{$name}\\Entities\\{$name}Entity;"],
            "\tpublic function findById(int \$id): ?{$name}Entity;" . PHP_EOL . PHP_EOL .
                "\tpublic function save({$name}Entity \${$nameCamel}): {$name}Entity;" . PHP_EOL . PHP_EOL .
                "\tpublic function delete({$name}Entity \${$nameCamel}): void;" . PHP_EOL . PHP_EOL .
                "\tpublic function paginate(int \$perPage, int \$page): array;",
            [],
            'interface'
        );
    }

    protected function buildContentValueObject(string $name): string
    {
        return $this->buildClassTemplate(
            "App\\Domain\\{$name}\\ValueObjects",
            "{$name}Status: string",
            [],
            "\tcase Public = 'public';" . PHP_EOL .
                "\tcase Private = 'private';" . PHP_EOL .
                "\tcase Hidden = 'hidden';",
            [],
            'enum'
        );
    }

    protected function buildContentEloquent(string $name): string
    {
        $nameLower = strtolower($name);

        return $this->buildClassTemplate(
            "App\\Infrastructure\\{$name}\\Persistence\\Eloquent",
            "{$name}Model",
            [
                "use Illuminate\\Database\\Eloquent\\Model;",
                "use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;",
                "use App\Models\\User;",
                "use App\\Domain\\{$name}\\ValueObjects\\{$name}Status;"
            ],
            "\tprotected \$table = '{$nameLower}';" . PHP_EOL . PHP_EOL .
                "\tprotected \$fillable = [" . PHP_EOL .
                "\t\t'created_by'," . PHP_EOL .
                "\t\t'updated_by'," . PHP_EOL .
                "\t];" . PHP_EOL . PHP_EOL .
                "\tprotected \$casts = [" . PHP_EOL .
                "\t\t'status' => {$name}Status::class," . PHP_EOL .
                "\t\t'created_by' => 'integer'," . PHP_EOL .
                "\t\t'updated_by' => 'integer'" . PHP_EOL .
                "\t];" . PHP_EOL . PHP_EOL .
                "\tpublic function createdBy(): BelongsTo" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\treturn \$this->belongsTo(User::class, 'created_by');" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tpublic function updatedBy(): BelongsTo" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\treturn \$this->belongsTo(User::class, 'updated_by');" . PHP_EOL .
                "\t}",
            ["extends Model"]
        );
    }

    protected function buildContentProvider(string $name): string
    {
        return $this->buildClassTemplate(
            "App\\Infrastructure\\{$name}\\Persistence\\Providers",
            "RepositoryServiceProvider",
            [
                "use Illuminate\Support\ServiceProvider;",
                "use App\\Domain\\{$name}\\Repositories\\{$name}RepositoryInterface;",
                "use App\\Infrastructure\\{$name}\\Persistence\\Repositories\\Eloquent{$name}Repository;"
            ],
            "\tpublic function register(): void" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\t\$this->app->bind({$name}RepositoryInterface::class, Eloquent{$name}Repository::class);" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tpublic function boot(): void" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t}",
            ["extends ServiceProvider"]
        );
    }

    protected function buildContentRepository(string $name): string
    {
        $nameCamel = lcfirst($name);

        return $this->buildClassTemplate(
            "App\\Infrastructure\\{$name}\\Persistence\\Repositories",
            "Eloquent{$name}Repository",
            [
                "use App\\Domain\\{$name}\\Entities\\{$name}Entity;",
                "use App\\Domain\\{$name}\\Repositories\\{$name}RepositoryInterface;",
                "use App\\Infrastructure\\{$name}\\Persistence\\Eloquent\\{$name}Model;"
            ],
            "\tpublic function findById(int \$id): ?{$name}Entity" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\t\$model = {$name}Model::find(\$id);" . PHP_EOL .
                "\t\treturn \$model ? \$this->toEntity(\$model) : null;" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tpublic function save({$name}Entity \${$nameCamel}): {$name}Entity" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\tif (\${$nameCamel}->getId()) {" . PHP_EOL .
                "\t\t\t\$model = {$name}Model::find(\${$nameCamel}->getId());" . PHP_EOL .
                "\t\t\tif (!\$model) {" . PHP_EOL .
                "\t\t\t\t\$model = new {$name}Model();" . PHP_EOL .
                "\t\t\t}" . PHP_EOL .
                "\t\t} else {" . PHP_EOL .
                "\t\t\t\$model = new {$name}Model();" . PHP_EOL .
                "\t\t}" . PHP_EOL . PHP_EOL .
                "\t\t\$this->fillModelFromEntity(\$model, \${$nameCamel})->save();" . PHP_EOL . PHP_EOL .
                "\t\t\${$nameCamel}->setId(\$model->id);" . PHP_EOL .
                "\t\t\${$nameCamel}->setCreatedAt(\$model->created_at);" . PHP_EOL .
                "\t\t\${$nameCamel}->setUpdatedAt(\$model->updated_at);" . PHP_EOL . PHP_EOL .
                "\t\treturn \${$nameCamel};" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tpublic function delete({$name}Entity \${$nameCamel}): void" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\tif (\$model = {$name}Model::find(\${$nameCamel}->getId())) {" . PHP_EOL .
                "\t\t\t\$model->delete();" . PHP_EOL .
                "\t\t}" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tpublic function paginate(int \$perPage, int \$page): array" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\treturn {$name}Model::skip((\$page - 1) * \$perPage)" . PHP_EOL .
                "\t\t\t->take(\$perPage)" . PHP_EOL .
                "\t\t\t->get()" . PHP_EOL .
                "\t\t\t->map(fn(\$model) => \$this->toEntity(\$model))" . PHP_EOL .
                "\t\t\t->all();" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tprivate function toEntity({$name}Model \$model): {$name}Entity" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\treturn new {$name}Entity(" . PHP_EOL .
                "\t\t\t\$model->id," . PHP_EOL .
                "\t\t\t\$model->created_at," . PHP_EOL .
                "\t\t\t\$model->created_by," . PHP_EOL .
                "\t\t\t\$model->updated_at," . PHP_EOL .
                "\t\t\t\$model->updated_by" . PHP_EOL .
                "\t\t);" . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                "\tprivate function fillModelFromEntity({$name}Model \$model, {$name}Entity \${$nameCamel}): {$name}Model" . PHP_EOL .
                "\t{" . PHP_EOL .
                "\t\t\$model->created_by = \${$nameCamel}->getCreatedBy();" . PHP_EOL .
                "\t\t\$model->updated_by = \${$nameCamel}->getUpdatedBy();" . PHP_EOL .
                "\t\t\$model->created_at = \${$nameCamel}->getCreatedAt();" . PHP_EOL .
                "\t\t\$model->updated_at = \${$nameCamel}->getUpdatedAt();" . PHP_EOL .
                "\t\treturn \$model;" . PHP_EOL .
                "\t}",
            ["implements {$name}RepositoryInterface"]
        );
    }

    protected function buildContentController(string $name): string
    {
        $uses = [
            "use App\\Application\\{$name}\\ActionHandlers\\List{$name}ActionHandler;",
            "use App\\Application\\{$name}\\ActionHandlers\\Show{$name}ActionHandler;",
            "use App\\Application\\{$name}\\ActionHandlers\\Create{$name}ActionHandler;",
            "use App\\Application\\{$name}\\ActionHandlers\\Delete{$name}ActionHandler;",
            "use App\\Application\\{$name}\\ActionHandlers\\Update{$name}ActionHandler;",
            "use App\\Application\\{$name}\\Actions\\Create{$name}Action;",
            "use App\\Application\\{$name}\\Actions\\Delete{$name}Action;",
            "use App\\Application\\{$name}\\Actions\\List{$name}Action;",
            "use App\\Application\\{$name}\\Actions\\Show{$name}Action;",
            "use App\\Application\\{$name}\\Actions\\Update{$name}Action;",
            "use App\\Application\\{$name}\\DTOs\\{$name}Data;",
            "use App\\Http\\Helpers;",
            "use Illuminate\\Http\\Request;",
            "use Illuminate\\Http\\JsonResponse;",
            "use App\Http\Controllers\Controller;"
        ];

        $properties = [
            "protected List{$name}ActionHandler \$listActionHandler;",
            "protected Show{$name}ActionHandler \$showActionHandler;",
            "protected Create{$name}ActionHandler \$createActionHandler;",
            "protected Update{$name}ActionHandler \$updateActionHandler;",
            "protected Delete{$name}ActionHandler \$deleteActionHandler;"
        ];

        $constructorParams = [
            "List{$name}ActionHandler \$listActionHandler",
            "Show{$name}ActionHandler \$showActionHandler",
            "Create{$name}ActionHandler \$createActionHandler",
            "Update{$name}ActionHandler \$updateActionHandler",
            "Delete{$name}ActionHandler \$deleteActionHandler"
        ];

        $constructorAssignments = array_map(
            fn($prop) =>
            "\t\t\$this->" . substr(explode(' ', $prop)[2], 1, strlen(explode(' ', $prop)[2]) - 2) . " = " . explode(' ', $prop)[2],
            $properties
        );

        $methods = [
            $this->buildControllerMethod($name, 'index', 'List', [], '200'),
            $this->buildControllerMethod($name, 'show', 'Show', ['int $id'], '200'),
            $this->buildControllerMethod($name, 'store', 'Create', ['Request $request'], '201', true),
            $this->buildControllerMethod($name, 'update', 'Update', ['Request $request', 'int $id'], '201', true, ["\$id", "\$data"]),
            $this->buildControllerMethod($name, 'destroy', 'Delete', ['int $id'], '204')
        ];

        return $this->buildClassTemplate(
            "App\\Presentation\\{$name}\\Controllers",
            "{$name}ApiController",
            $uses,
            "\tpublic function __construct(" . PHP_EOL .
                "\t\t" . implode(',' . PHP_EOL . "\t\t", $constructorParams) . PHP_EOL .
                "\t) {" . PHP_EOL .
                implode(PHP_EOL, $constructorAssignments) . PHP_EOL .
                "\t}" . PHP_EOL . PHP_EOL .
                implode(PHP_EOL . PHP_EOL, $methods),
            ['extends Controller'],
            'class',
            $properties
        );
    }

    protected function normalizeName(string $string): string
    {
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);
        return str_replace(' ', '', $string);
    }

    protected function toCamelCase(string $string): string
    {
        return lcfirst($this->normalizeName($string));
    }

    protected function buildClassTemplate(
        string $namespace,
        string $className,
        array $uses = [],
        string $content,
        array $classDeclarations = [],
        string $type = 'class',
        array $properties = []
    ): string {
        $uses = !empty($uses) ? implode(PHP_EOL, $uses) . PHP_EOL . PHP_EOL : '';
        $declarations = !empty($classDeclarations) ? implode(",", $classDeclarations) : '';
        $propertie = !empty($properties) ? "\t" . implode(PHP_EOL . "\t", $properties) . PHP_EOL . PHP_EOL : '';

        return PHP_EOL .
            "namespace {$namespace};" . PHP_EOL . PHP_EOL .
            $uses .
            "{$type} {$className} {$declarations}" . PHP_EOL .
            "{" . PHP_EOL .
            "{$propertie}" .
            $content . PHP_EOL .
            "}" . PHP_EOL;
    }

    protected function buildControllerMethod(
        string $name,
        string $methodName,
        string $actionType,
        array $params,
        string $responseCode,
        bool $includeData = false,
        array $paramsEx = []
    ): string {
        $paramString = implode(', ', $params);

        $methodContent = "\tpublic function {$methodName}(";
        $methodContent .= $paramString . "): JsonResponse" . PHP_EOL . "\t{" . PHP_EOL;

        if ($includeData) {
            $methodContent .= "\t\t\$data = new {$name}Data();" . PHP_EOL ;
        }
        $actionTypeCamel = $this->toCamelCase($actionType);

        $actionParams = $includeData
            ? "\$data"
            : (in_array('int $id', $params) ? "\$id" : '');

        if (!empty($paramsEx)) {
            $actionParams =  implode(', ', $paramsEx);
        }

        $methodContent .= "\t\t\$data = \$this->{$actionTypeCamel}ActionHandler->handle(new {$actionType}{$name}Action({$actionParams}));" . PHP_EOL;

        if ($includeData) {
            $methodContent .= "\t\treturn response()->json(\$data->toArray(), {$responseCode});";
        } else {
            $methodContent .= "\t\treturn response()->json(\$data, {$responseCode});";
        }

        return $methodContent . PHP_EOL . "\t}";
    }

    protected function buildCreateHandlerContent(string $name, string $nameCamel, string $entityName): string
    {
        return "\tprivate {$name}RepositoryInterface \${$nameCamel}Repository;" . PHP_EOL . PHP_EOL .
            "\tpublic function __construct({$name}RepositoryInterface \${$nameCamel}Repository)"  . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$this->{$nameCamel}Repository = \${$nameCamel}Repository;" . PHP_EOL .
            "\t}" . PHP_EOL . PHP_EOL .
            "\tpublic function handle(Create{$name}Action \$action): {$entityName}" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$data = \$action->{$nameCamel}Data;" . PHP_EOL .
            "\t\t\${$nameCamel} = new {$entityName}(" . PHP_EOL .
            "\t\t\tnull," . PHP_EOL .
            "\t\t\t\$data->created_at," . PHP_EOL .
            "\t\t\t\$data->created_by," . PHP_EOL .
            "\t\t\t\$data->updated_at," . PHP_EOL .
            "\t\t\t\$data->updated_by" . PHP_EOL .
            "\t\t);" . PHP_EOL .
            "\t\treturn \$this->{$nameCamel}Repository->save(\${$nameCamel});" . PHP_EOL .
            "\t}";
    }

    protected function buildDeleteHandlerContent(string $name, string $nameCamel): string
    {
        return "\tprivate {$name}RepositoryInterface \${$nameCamel}Repository;" . PHP_EOL . PHP_EOL .
            "\tpublic function __construct({$name}RepositoryInterface \${$nameCamel}Repository)"  . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$this->{$nameCamel}Repository = \${$nameCamel}Repository;" . PHP_EOL .
            "\t}" . PHP_EOL . PHP_EOL .
            "\tpublic function handle(Delete{$name}Action \$action): void" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\${$nameCamel} = \$this->{$nameCamel}Repository->findById(\$action->id);" . PHP_EOL .
            "\t\tif (\${$nameCamel} !== null) {" . PHP_EOL .
            "\t\t\t\$this->{$nameCamel}Repository->delete(\${$nameCamel});" . PHP_EOL .
            "\t\t}" . PHP_EOL .
            "\t}";
    }

    protected function buildListHandlerContent(string $name, string $nameCamel): string
    {
        return "\tprivate {$name}RepositoryInterface \${$nameCamel}Repository;" . PHP_EOL . PHP_EOL .
            "\tpublic function __construct({$name}RepositoryInterface \${$nameCamel}Repository)"  . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$this->{$nameCamel}Repository = \${$nameCamel}Repository;" . PHP_EOL .
            "\t}" . PHP_EOL . PHP_EOL .
            "\tpublic function handle(List{$name}Action \$action): array" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$results = \$this->{$nameCamel}Repository->paginate(\$action->perPage, \$action->page);" . PHP_EOL .
            "\t\treturn array_map(function (\$entity) {" . PHP_EOL .
            "\t\t\treturn \$entity->toArray();" . PHP_EOL .
            "\t\t}, \$results);" . PHP_EOL .
            "\t}";
    }

    protected function buildShowHandlerContent(string $name, string $nameCamel): string
    {
        return "\tprivate {$name}RepositoryInterface \${$nameCamel}Repository;" . PHP_EOL . PHP_EOL .
            "\tpublic function __construct({$name}RepositoryInterface \${$nameCamel}Repository)"  . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$this->{$nameCamel}Repository = \${$nameCamel}Repository;" . PHP_EOL .
            "\t}" . PHP_EOL . PHP_EOL .
            "\tpublic function handle(Show{$name}Action \$action): ?{$name}Data" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\${$nameCamel} = \$this->{$nameCamel}Repository->findById(\$action->id);" . PHP_EOL .
            "\t\tif (\${$nameCamel} === null) {" . PHP_EOL .
            "\t\t\treturn null;" . PHP_EOL .
            "\t\t};" . PHP_EOL .
            "\t\treturn new {$name}Data(" . PHP_EOL .
            "\t\t\t\${$nameCamel}->getCreatedBy()," . PHP_EOL .
            "\t\t\t\${$nameCamel}->getUpdatedBy()," . PHP_EOL .
            "\t\t\t\${$nameCamel}->getCreatedAt()," . PHP_EOL .
            "\t\t\t\${$nameCamel}->getUpdatedAt()" . PHP_EOL .
            "\t\t);" . PHP_EOL .
            "\t}";
    }

    protected function buildUpdateHandlerContent(string $name, string $nameCamel, string $entityName): string
    {
        return "\tprivate {$name}RepositoryInterface \${$nameCamel}Repository;" . PHP_EOL . PHP_EOL .
            "\tpublic function __construct({$name}RepositoryInterface \${$nameCamel}Repository)"  . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\$this->{$nameCamel}Repository = \${$nameCamel}Repository;" . PHP_EOL .
            "\t}" . PHP_EOL . PHP_EOL .
            "\tpublic function handle(Update{$name}Action \$action): ?{$entityName}" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\t\${$nameCamel} = \$this->{$nameCamel}Repository->findById(\$action->id);" . PHP_EOL .
            "\t\tif (\${$nameCamel} === null) {" . PHP_EOL .
            "\t\t\treturn null;" . PHP_EOL .
            "\t\t};" . PHP_EOL .
            "\t\t\$data = \$action->{$nameCamel}Data;" . PHP_EOL .
            "\t\t\${$nameCamel}->setUpdatedBy(\$data->updated_by);" . PHP_EOL .
            "\t\t\${$nameCamel}->setUpdatedAt(\$data->updated_at);" . PHP_EOL .
            "\t\treturn \$this->{$nameCamel}Repository->save(\${$nameCamel});" . PHP_EOL .
            "\t}";
    }

    private function updateRouter($name, $nameCal, $isRemove = false)
    {
        $path = base_path('routes') . '/api.php';
        $contenFile = file_get_contents($path);
        $start = PHP_EOL . PHP_EOL . "/** Start route {$name}ApiController **/";
        $end = "/** End route {$name}ApiController **/";
        $indexStart = strpos($contenFile, $start);
        $indexEnd = strpos($contenFile, $end);
        $content = "use App\\Presentation\\{$name}\\Controllers\\{$name}ApiController;" . PHP_EOL .
            "Route::apiResource('{$nameCal}', {$name}ApiController::class);" . PHP_EOL .
            "//\$this->app->bind({$name}RepositoryInterface::class, Eloquent{$name}Repository::class);" . PHP_EOL;
        $newContent =  $isRemove ? '' : $start . PHP_EOL . $content . $end;
        if (is_numeric($indexStart) && is_numeric($indexEnd)) {
            $oldContent = substr($contenFile, $indexStart, $indexEnd + strlen($end));
            $contenFile = str_replace($oldContent, $newContent, $contenFile);
            File::put($path, $contenFile);
        } else {
            File::append($path, $newContent);
        }
    }

    private function createMigrate($name)
    {
        $path = base_path('database') . '/migrations/';
        $nameMigate  = now()->format('Y_m_d_His') . '_create_' . $name . '_table';
        $content = PHP_EOL . "use Illuminate\Database\Migrations\Migration;" . PHP_EOL .
            "use Illuminate\Database\Schema\Blueprint;" . PHP_EOL .
            "use Illuminate\Support\Facades\Schema;" . PHP_EOL .
            PHP_EOL .
            "return new class extends Migration" . PHP_EOL .
            "{" . PHP_EOL .
            "\tpublic function up(): void" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\tif (!Schema::hasTable('{$name}')) {" . PHP_EOL .
            "\t\t\tSchema::create('{$name}', function (Blueprint \$table) {" . PHP_EOL .
            "\t\t\t\t\$table->id();" . PHP_EOL .
            "\t\t\t\t\$table->unsignedBigInteger('created_by')->nullable();" . PHP_EOL .
            "\t\t\t\t\$table->unsignedBigInteger('updated_by')->nullable();" . PHP_EOL .
            "\t\t\t\t\$table->timestamps();" . PHP_EOL .
            "\t\t\t\t\$table->foreign('created_by')->references('id')->on('users')->onDelete('set null');" . PHP_EOL .
            "\t\t\t\t\$table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');" . PHP_EOL .
            "\t\t\t});" . PHP_EOL .
            "\t\t}" . PHP_EOL .
            "\t}" . PHP_EOL .
            PHP_EOL .
            "\tpublic function down(): void" . PHP_EOL .
            "\t{" . PHP_EOL .
            "\t\tSchema::dropIfExists('{$name}');" . PHP_EOL .
            "\t}" . PHP_EOL .
            "};";
        $this->createFile($path, $nameMigate, '.php', $content);
    }
}
