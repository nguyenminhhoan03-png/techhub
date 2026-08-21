<?php

declare(strict_types=1);

namespace Application\Tool\CommandHandlers;

use Application\Bus\CommandHandler;
use Application\Tool\Commands\ExecuteToolCommand;
use Application\Tool\Data\ToolExecutionData;
use Domain\Tool\Contracts\ToolRegistryContract;
use Domain\Tool\Enums\ToolExecutionStatus;
use Domain\Tool\Repositories\ToolRepositoryContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class ExecuteToolCommandHandler extends CommandHandler
{
    public function __construct(
        private readonly ToolRegistryContract $toolRegistry,
        private readonly ToolRepositoryContract $toolRepository,
    ) {}

    public function handle(ExecuteToolCommand $command): ToolExecutionData
    {
        $tool = $this->toolRegistry->get($command->toolSlug);
        if ( ! $tool) {
            throw new InvalidArgumentException("Tool [{$command->toolSlug}] is not registered or supported.");
        }

        // Validate input against tool-specific validation rules
        $rules = $tool->validationRules();
        if ( ! empty($rules)) {
            $validator = Validator::make($command->input, $rules);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $validatedInput = (array) $validator->validated();
        } else {
            $validatedInput = $command->input;
        }

        // Execute tool logic
        $toolResult = $tool->execute($validatedInput);

        // Fetch tool entity from database to link and update stats (if available)
        $toolEntity = $this->toolRepository->getToolBySlug($command->toolSlug);
        $executionId = null;

        if ($toolEntity) {
            $this->toolRepository->incrementExecutionCount($toolEntity->id);

            // Log execution history
            $execution = $this->toolRepository->createExecution([
                'tool_id' => $toolEntity->id,
                'user_id' => $command->userId,
                'ip_address' => $command->ipAddress,
                'status' => $toolResult->isSuccess ? ToolExecutionStatus::Completed : ToolExecutionStatus::Failed,
                'execution_time_ms' => $toolResult->executionTimeMs ?? 0,
                'input_size_bytes' => mb_strlen((string) json_encode($validatedInput)),
                'output_size_bytes' => $toolResult->outputSizeBytes ?? mb_strlen((string) json_encode($toolResult->data)),
                'result_file_path' => $toolResult->outputFilePath,
                'error_message' => $toolResult->errorMessage,
                'input_meta' => $toolResult->meta,
            ]);

            $executionId = $execution->ulid;
        }

        return new ToolExecutionData(
            success: $toolResult->isSuccess,
            tool_slug: $command->toolSlug,
            result_data: $toolResult->data,
            execution_time_ms: $toolResult->executionTimeMs ?? 0,
            execution_id: $executionId,
            error_message: $toolResult->errorMessage,
            download_url: $toolResult->outputFilePath,
            input_meta: $toolResult->meta,
        );
    }
}
