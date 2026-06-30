<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Policy\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Attribute-Based Access Control (ABAC) policy.
 * Evaluated after RBAC — allows row-level and field-level authorization.
 */
class Policy extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'resource',
        'action',
        'conditions',
        'effect',
        'priority',
    ];

    protected $casts = [
        'conditions' => 'array',
        'priority'   => 'integer',
    ];

    public function allows(array $context): bool
    {
        foreach ($this->conditions as $condition) {
            $field    = $condition['field'];
            $operator = $condition['operator'];
            $value    = $condition['value'];
            $actual   = $context[$field] ?? null;

            if (!$this->evaluate($actual, $operator, $value)) {
                return false;
            }
        }

        return $this->effect === 'allow';
    }

    private function evaluate(mixed $actual, string $operator, mixed $value): bool
    {
        return match ($operator) {
            '=='  => $actual == $value,
            '!='  => $actual != $value,
            'in'  => in_array($actual, (array) $value),
            'not_in' => !in_array($actual, (array) $value),
            default  => false,
        };
    }
}
