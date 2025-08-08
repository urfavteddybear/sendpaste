<?php

namespace Database\Seeders;

use App\Models\Paste;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestPasteSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create a simple test paste
        $paste = new Paste([
            'title' => 'Welcome to SendPaste',
            'language' => 'text',
            'expires_at' => now()->addWeek(),
            'ip_address' => '127.0.0.1',
        ]);
        
        $paste->content = "Welcome to SendPaste!\n\nThis is a secure, encrypted pastebin service.\n\nKey features:\n- All content is encrypted in the database\n- Automatic expiration\n- Password protection (optional)\n- Clean, minimalist design\n- API access\n\nTry creating your own paste!";
        $paste->save();
        
        // Create a code example
        $codePaste = new Paste([
            'title' => 'PHP Example',
            'language' => 'php',
            'expires_at' => now()->addMonth(),
            'ip_address' => '127.0.0.1',
        ]);
        
        $codePaste->content = "<?php\n\nclass SendPaste {\n    public function encrypt(\$content) {\n        return encrypt(\$content);\n    }\n    \n    public function createPaste(\$data) {\n        return Paste::create([\n            'content' => \$this->encrypt(\$data['content']),\n            'title' => \$data['title'],\n            'expires_at' => \$data['expires_at']\n        ]);\n    }\n}";
        $codePaste->save();
        
        $this->command->info('Created test pastes:');
        $this->command->line('- Welcome paste: ' . url('/' . $paste->slug));
        $this->command->line('- PHP example: ' . url('/' . $codePaste->slug));
    }
}
