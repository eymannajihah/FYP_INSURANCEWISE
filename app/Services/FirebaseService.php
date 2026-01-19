<?php

namespace App\Services;

use Kreait\Firebase\Database;

class FirebaseService
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    // Save data to a specific node
    public function save($path, $data)
    {
        $this->database->getReference($path)->set($data);
    }

    // Push new child (auto-generated key)
    public function push($path, $data)
    {
        $this->database->getReference($path)->push($data);
    }

    // Get data from a node
    public function get($path)
    {
        return $this->database->getReference($path)->getValue();
    }
}
