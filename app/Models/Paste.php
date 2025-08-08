<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class Paste extends Model
{
    protected $fillable = [
        'slug',
        'content',
        'title',
        'language',
        'expires_at',
        'password_hash',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'password_hash',
        'ip_address',
    ];

    protected $appends = [
        'url',
        'formatted_language',
    ];

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($paste) {
            if (empty($paste->slug)) {
                $paste->slug = static::generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique 6-character slug
     */
    public static function generateUniqueSlug(): string
    {
        do {
            $slug = Str::random(6);
        } while (static::where('slug', $slug)->exists());
        
        return $slug;
    }

    /**
     * Encrypt content before saving
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt content when retrieving
     */
    public function getContentAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Return as-is if decryption fails
        }
    }

    /**
     * Set content (public method)
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Check if paste is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if paste has password protection
     */
    public function isPasswordProtected(): bool
    {
        return !empty($this->password_hash);
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return $this->isPasswordProtected() && password_verify($password, $this->password_hash);
    }

    /**
     * Set password
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Scope to get non-expired pastes
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Get URL for this paste
     */
    public function getUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }

    /**
     * Get formatted language name for display
     */
    public function getFormattedLanguageAttribute(): string
    {
        $languageMap = [
            'plaintext' => 'Plain Text',
            'text' => 'Plain Text',
            'javascript' => 'JavaScript',
            'typescript' => 'TypeScript',
            'csharp' => 'C#',
            'cpp' => 'C++',
            'dockerfile' => 'Dockerfile',
            'powershell' => 'PowerShell',
            'postgresql' => 'PostgreSQL',
            'mongodb' => 'MongoDB',
            'mysql' => 'MySQL',
            'html' => 'HTML',
            'css' => 'CSS',
            'json' => 'JSON',
            'xml' => 'XML',
            'yaml' => 'YAML',
            'toml' => 'TOML',
            'ini' => 'INI',
            'csv' => 'CSV',
            'sql' => 'SQL',
            'bash' => 'Bash',
            'shell' => 'Shell',
            'batch' => 'Batch',
            'markdown' => 'Markdown',
            'latex' => 'LaTeX',
            'vue' => 'Vue',
            'react' => 'React (JSX)',
        ];

        return $languageMap[$this->language] ?? ucfirst($this->language ?? 'plaintext');
    }
}
