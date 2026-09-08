<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Exception;

class XmlToJsonTool implements ToolContract
{
    public function slug(): string
    {
        return 'xml-to-json';
    }

    public function name(): string
    {
        return 'XML to JSON Converter';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Parse and convert XML document trees and RSS feeds into clean, standardized JSON objects.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'xml' => ['required', 'string'],
            'minify' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawXml = trim((string) ($input['xml'] ?? ''));
        $minify = (bool) ($input['minify'] ?? false);

        if (empty($rawXml)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('XML content cannot be empty.', $executionTimeMs);
        }

        libxml_use_internal_errors(true);
        $xmlObj = simplexml_load_string($rawXml, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (false === $xmlObj) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            $msg = ! empty($errors) ? $errors[0]->message . ' on line ' . $errors[0]->line : 'Malformed XML syntax.';
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid XML: ' . trim($msg), $executionTimeMs);
        }

        libxml_clear_errors();

        try {
            // Convert SimpleXML to associative array
            $encoded = json_encode($xmlObj, JSON_THROW_ON_ERROR);
            $array = json_decode($encoded, true, 512, JSON_THROW_ON_ERROR);

            $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
            if ( ! $minify) {
                $flags |= JSON_PRETTY_PRINT;
            }

            $jsonResult = json_encode($array, $flags);
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => (string) $jsonResult,
                'root_element' => $xmlObj->getName(),
                'size_bytes' => mb_strlen((string) $jsonResult),
            ], executionTimeMs: $executionTimeMs);

        } catch (Exception $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('XML Conversion error: ' . $e->getMessage(), $executionTimeMs);
        }
    }
}
