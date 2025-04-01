<?php

use App\Models\Groups;
use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //php artisan db:seed --class=GroupsSeeder
    $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

    foreach ($groups as $group) {
      Groups::create([
        "name" => $group,
        'created_at' => now(),
        'updated_at' => now()
      ]);
    }
  }
}
