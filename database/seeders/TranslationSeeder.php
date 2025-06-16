<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Translation;
use App\Models\Language;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'es', 'name' => 'Spanish'],
        ];

        foreach ($languages as $lang) {
            Language::firstOrCreate(['code' => $lang['code']], ['name' => $lang['name']]);
        }

        $translations = [
            ['key' => 'welcome', 'values' => [
                'en' => 'Welcome',
                'fr' => 'Bienvenue',
                'es' => 'Bienvenido'
            ], 'tags' => ['web', 'mobile']],

            ['key' => 'logout', 'values' => [
                'en' => 'Logout',
                'fr' => 'Déconnexion',
                'es' => 'Cerrar sesión'
            ], 'tags' => ['web']],

            ['key' => 'save_changes', 'values' => [
                'en' => 'Save Changes',
                'fr' => 'Enregistrer les modifications',
                'es' => 'Guardar cambios'
            ], 'tags' => ['desktop', 'settings']]
        ];

        foreach ($translations as $item) {
            foreach ($item['values'] as $langCode => $value) {
                $language = Language::where('code', $langCode)->first();

                Translation::create([
                    'key' => $item['key'],
                    'value' => $value,
                    'language_id' => $language->id,
                    'tags' => $item['tags']
                ]);
            }
        }
    }
}
