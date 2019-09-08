<?php


namespace App\Links;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompressedLink extends BaseModel
{

    protected $fillable = ['link'];

    protected $rules = [
        'link' => 'required|max:2000',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
