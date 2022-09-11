<?php

namespace Kanata\Forklift\Tests\Samples;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryModelSample extends Model
{
    use HasFactory;

    protected $table = 'directories';

    protected $fillable = [
        'title',
        'parent',
    ];
}
