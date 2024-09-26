<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;
    const NOT_STARTED=0;
    const PENDING =1;
    const COMPLETED =2;
    protected  $guarded =[];

    public static function createSlug($name)
    {
        $slug = Str::slug($name).'-'. generateRandomCode();
        return $slug;
    }

    public function task_progress()    {
        return $this->hasOne(TaskProgress::class,'projectId');
    }

}
