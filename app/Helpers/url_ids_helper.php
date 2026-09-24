<?php

use CodeIgniter\Model;

if (! function_exists('public_id')) {
    /**
     * Return the stable SHA-1 representation used in public URLs.
     */
    function public_id(int|string $id): string
    {
        return sha1((string) ((int) $id));
    }
}

if (! function_exists('resolve_public_id')) {
    /**
     * Resolve a public SHA-1 ID to its database primary key.
     */
    function resolve_public_id(string $value, Model $model): ?int
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (! preg_match('/^[a-f0-9]{40}$/i', $value)) {
            return null;
        }

        static $idCache = [];
        $modelKey = get_class($model);
        if (! isset($idCache[$modelKey])) {
            $lookupModel = new $modelKey();
            $primaryKey = $lookupModel->getPrimaryKey();
            $idCache[$modelKey] = array_map(
                static fn (array $row): int => (int) $row[$primaryKey],
                $lookupModel->select($primaryKey)->findAll(),
            );
        }

        $value = strtolower($value);
        foreach ($idCache[$modelKey] as $id) {
            if (hash_equals(public_id($id), $value)) {
                return $id;
            }
        }

        return null;
    }
}
