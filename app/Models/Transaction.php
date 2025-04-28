<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function Student() {
        return $this->belongsTo(Student::class, 'id_student', 'code');
    }

    public function Invoice() {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }
}
