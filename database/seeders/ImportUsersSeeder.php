<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUsersSeeder extends Seeder
{
    public function run()
    {
        
        $filePath = base_path('members.csv');

     
        if (!file_exists($filePath)) {
            $this->command->error("The file does not exist at: {$filePath}");
            return;
        }

      
        $csvData = array_map('str_getcsv', file($filePath));

       
        $header = array_shift($csvData);

        foreach ($csvData as $row) {
            $data = array_combine($header, $row);
            $fullName = $data['First Name'] . ' ' . $data['Last Name'];
            $slug = Str::slug($fullName);
            $slugCount = 1;
            while (User::where('slug', $slug)->exists()) {
                $slug = Str::slug($fullName) . '-' . $slugCount++;
            }
            $password = strtolower(str_replace(' ', '', $data['Last Name']) . '1234');
            User::create([
                'first_name' => $data['First Name'],
                'last_name' => $data['Last Name'],
                'email' => $data['Email Address'],
                'slug' => $slug,
                'password' => Hash::make($password),
            ]);
        }
  
        $this->command->info('Users have been imported successfully!');
    }
}
