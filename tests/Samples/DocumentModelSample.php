<?php

namespace Kanata\Forklift\Tests\Samples;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentModelSample extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'title',
        'content',
        'directory_id',
    ];
}
