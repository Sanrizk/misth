<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUsage extends Model
{
    use HasFactory;

    protected $table = 'material_usages';
    
    protected $fillable = [
        'maintenance_log_id', 'material_id',
        'quantity_used', 'notes'
    ];

    public function maintenanceLog() {
        return $this->belongsTo(MaintenanceLog::class, 'maintenance_log_id');
    }

    public function material() {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
