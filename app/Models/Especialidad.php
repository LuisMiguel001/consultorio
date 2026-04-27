<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    protected $fillable = ['nombre', 'slug'];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
