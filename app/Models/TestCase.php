<?php
// app/Models/TestCase.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    // Sesuaikan dengan nama tabelmu jika berbeda
    protected $table = 'test_cases'; 
    
    // Sesuaikan dengan kolom di databasemu
    protected $fillable = ['task_id', 'stdin', 'expected_output']; 
}