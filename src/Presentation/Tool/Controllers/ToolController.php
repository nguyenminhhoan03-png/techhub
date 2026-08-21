<?php

declare(strict_types=1);

namespace Presentation\Tool\Controllers;

use Application\Tool\Contracts\ToolServiceContract;
use Application\Tool\Data\ToolCategoryData;
use Application\Tool\Data\ToolData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Presentation\Controller;
use Presentation\Tool\Requests\ExecuteToolRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToolController extends Controller
{
    public function __construct(private readonly ToolServiceContract $toolService) {}

    /**
     * Get all active tool categories.
     *
     * @return Collection<int, ToolCategoryData>
     */
    public function indexCategories(): Collection
    {
        return $this->toolService->getCategories();
    }

    /**
     * List all active tools, optionally filtered by category or search term.
     *
     * @return Collection<int, ToolData>
     */
    public function index(Request $request): Collection
    {
        $category = $request->query('category');
        $search = $request->query('search');

        return $this->toolService->getTools(
            categorySlug: is_string($category) ? $category : null,
            search: is_string($search) ? $search : null,
        );
    }

    /**
     * Get single tool details by slug.
     */
    public function show(string $slug): ToolData
    {
        $tool = $this->toolService->getToolBySlug($slug);
        if ( ! $tool) {
            throw new NotFoundHttpException("Tool [{$slug}] not found.");
        }

        return $tool;
    }

    /**
     * Execute a tool by slug.
     */
    public function execute(string $slug, ExecuteToolRequest $request): JsonResponse
    {
        $input = (array) $request->input('input', []);
        $userId = Auth::id() ? (int) Auth::id() : null;
        $ipAddress = $request->ip() ?: '127.0.0.1';

        $result = $this->toolService->executeTool(
            slug: $slug,
            input: $input,
            userId: $userId,
            ipAddress: $ipAddress,
        );

        return response()->json([
            'success' => $result->success,
            'message' => $result->success ? 'Tool executed successfully.' : 'Tool execution failed.',
            'data' => $result,
        ], $result->success ? 200 : 422);
    }
}
