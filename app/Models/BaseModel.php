<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Shared application model contract.
 *
 * CodeIgniter's primaryKey property is protected and Model does not expose a
 * getPrimaryKey() method. CRUD helpers use this accessor instead of relying on
 * framework internals or calling an undefined method on CodeIgniter\Model.
 */
abstract class BaseModel extends Model
{
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}
