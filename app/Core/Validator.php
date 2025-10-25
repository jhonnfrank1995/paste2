<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
private array $errors = [];
private array $data = [];

public function validate(array $data, array $rules): bool
{
$this->errors = [];
$this->data = $data;

foreach ($rules as $field => $fieldRules) {
$rulesArray = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
$value = $this->data[$field] ?? null;

foreach ($rulesArray as $rule) {
$params = [];
if (strpos($rule, ':') !== false) {
[$rule, $paramStr] = explode(':', $rule, 2);
$params = explode(',', $paramStr);
}

$methodName = 'validate' . ucfirst($rule);
if (method_exists($this, $methodName)) {
$this->{$methodName}($field, $value, $params);
}
}
}
return empty($this->errors);
}

public function getErrors(): array { return $this->errors; }
public function getFirstError(string $field): ?string { return $this->errors[$field][0] ?? null; }

private function addError(string $field, string $message) { $this->errors[$field][] = $message; }

// --- Reglas de Validación ---
private function validateRequired(string $field, $value): void {
if (is_null($value) || (is_string($value) && trim($value) === '')) {
$this->addError($field, "The {$field} field is required.");
}
}
private function validateEmail(string $field, $value): void {
if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
$this->addError($field, "The {$field} must be a valid email address.");
}
}
private function validateMin(string $field, $value, array $params): void {
$min = (int) $params[0];
if (mb_strlen($value) < $min) {
$this->addError($field, "The {$field} must be at least {$min} characters.");
}
}
private function validateMax(string $field, $value, array $params): void {
$max = (int) $params[0];
if (mb_strlen($value) > $max) {
$this->addError($field, "The {$field} may not be greater than {$max} characters.");
}
}
private function validateConfirmed(string $field, $value): void {
if ($value !== ($this->data[$field . '_confirmation'] ?? null)) {
$this->addError($field, "The {$field} confirmation does not match.");
}
}
}