<?php

// namespace Database\Seeders;
// use App\Models\Users\User;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

// class ImportUsersSeeder extends Seeder
// {
//     public function run()
//     {
        
//         $filePath = base_path('members.csv');

     
//         if (!file_exists($filePath)) {
//             $this->command->error("The file does not exist at: {$filePath}");
//             return;
//         }

      
//         $csvData = array_map('str_getcsv', file($filePath));

       
//         $header = array_shift($csvData);

//         foreach ($csvData as $row) {
//             $data = array_combine($header, $row);
//             $fullName = $data['First Name'] . ' ' . $data['Last Name'];
//             $slug = Str::slug($fullName);
//             $slugCount = 1;
//             while (User::where('slug', $slug)->exists()) {
//                 $slug = Str::slug($fullName) . '-' . $slugCount++;
//             }
//             $password = strtolower(str_replace(' ', '', $data['Last Name']) . '1234');
//             User::create([
//                 'first_name' => $data['First Name'],
//                 'last_name' => $data['Last Name'],
//                 'email' => $data['Email Address'],
//                 'slug' => $slug,
//                 'password' => Hash::make($password),
//             ]);
//         }
  
//         $this->command->info('Users have been imported successfully!');
//     }
// }



namespace Database\Seeders;

use App\Models\Users\User;
use App\Models\Business\Company;
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

            $linkedinUrl = $data['Person Linkedin Url'] ?? 'N/A';
            if (stripos($linkedinUrl, 'linkedin.com/in/') !== false) {
                $linkedinUrl = str_replace(['http://linkedin.com/in/', 'https://linkedin.com/in/', 'https://www.linkedin.com/in/', 'http://www.linkedin.com/in/'], '', $linkedinUrl);
            }

            $data = array_map(function ($value) {
                return $value ?: 'N/A';
            }, $data);

            $password = strtolower(str_replace(' ', '', $data['Last Name']) . '1234');
            $user = User::updateOrCreate(
                ['email' => $data['Email']],
                [
                    'first_name' => $data['First Name'],
                    'last_name' => $data['Last Name'],
                    'phone' => $data['Primary Phone'],
                    'city' => $data['City'],
                    'state' => $data['State'],
                    'country' => $data['Country'],
                    'linkedin_url' => $linkedinUrl,
                    'slug' => $this->generateUniqueSlug($data['First Name'], $data['Last Name']),
                    'password' => Hash::make($password),
                ]
            );

            $this->command->info('Creating Company for User: ' . $user->email);
            $this->command->info('Data: ' . json_encode([
                'company_name' => $data['Company'],
                'company_position' => $data['Title'],
                'company_industry' => $data['Industry'],
                'company_web_url' => $data['Website'],
            ]));

            Company::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $data['Company'],
                    'company_position' => $data['Title'],
                    'company_industry' => $data['Industry'] ?: 'N/A',
                    'company_web_url' => $data['Website'] ?: 'N/A',
                ]
            );
        }

        $this->command->info('Users and Companies have been imported successfully!');
    }

    private function generateUniqueSlug($firstName, $lastName)
    {
        $fullName = $firstName . ' ' . $lastName;
        $slug = Str::slug($fullName);
        $slugCount = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = Str::slug($fullName) . '-' . $slugCount++;
        }

        return $slug;
    }
}

