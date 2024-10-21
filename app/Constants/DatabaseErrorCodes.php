<?php

namespace App\Constants;

class DatabaseErrorCodes
{
    // Código de error de violación de restricción única
    public const UNIQUE_CONSTRAINT_VIOLATION_CODE = '23000';

    // Código de error de restricción de clave foránea
    public const FOREIGN_KEY_CONSTRAINT_VIOLATION_CODE = '23503';

    // Código de error de restricción de not null
    public const NOT_NULL_VIOLATION_CODE = '23502';

    // Código de error de restricción de verificación (CHECK constraint)
    public const CHECK_CONSTRAINT_VIOLATION_CODE = '23514';

    // Código de error de violación de restricción de valor único o duplicado
    public const DUPLICATE_KEY_VIOLATION_CODE = '23505';

    // Código de error de violación de transacción serializable
    public const SERIALIZATION_FAILURE_CODE = '40001';

    // Código de error de error de deadlock (bloqueo de recursos)
    public const DEADLOCK_DETECTED_CODE = '40P01';

    // Código de error de tipo de dato inválido
    public const INVALID_DATA_TYPE_CODE = '22000';

    // Código de error de integridad referencial
    public const INTEGRITY_CONSTRAINT_VIOLATION_CODE = '23001';

    // Código de error de sintaxis SQL inválida
    public const SYNTAX_ERROR_OR_ACCESS_RULE_VIOLATION_CODE = '42000';
}
