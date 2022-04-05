<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserV1Seeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createOrUpdateUser("prof@prof.com","prof@eduvaud.ch","prof","esseur","prof");
        $this->createOrUpdateUser("root@r.com","root@eduvaud.ch","ro","ot","root");
        $this->createOrUpdateUser("mp@mp.com","mp@eduvaud.ch","mp","rincipal","mp");
        $this->createOrUpdateUser("padawan@mp.com","padawan@eduvaud.ch","pada","wan","eleve");


        //Creates 24 teachers
        foreach(User::factory()->count(24)->create() as $user)
        {
            $user->assignRole('prof');
        }

        //Creates 250 students
        foreach(User::factory()->count(250)->create() as $user)
        {
            $user->assignRole('eleve');
        }
    }

    public function createOrUpdateUser($email,$username,$fn,$ln,...$roles)
    {
        $user = User::updateOrCreate([
                'firstname' => $fn,
                'lastname' => $ln,
                'email' => $email,
                'username' => $username
            ]);

        //reset
        $user->syncRoles([]);

        //fill
        $user->assignRole($roles);
    }

}
