<?php

use App\Models\User;
use Database\Seeders\PermissionV1Seeder;

beforeEach(function()
{
    //$this->seed(\Database\Seeders\JobSeeder::class);
});

it('Student can apply for a job', function () {

    $eleve = $this->CreateUser(roles: 'eleve');
    $prof = $this->CreateUser(false,'prof');

    $job = \App\Models\JobDefinition::factory()
        ->afterCreating(function(\App\Models\JobDefinition $job) use($prof)
        {
            $job->providers()->attach($prof->id);
        })
        ->create();

    $this->visit("/jobs-apply/".$job->id)
        ->type(now(), 'start_date')
        ->type(now(), 'end_date')
        ->type($job->id, 'job_definition_id')
        ->select($prof->id, 'client')
        ->press(__('Apply'))
        ->seePageIs('/dashboard')
        ->seeText('Congrats, you have been hired for the job')
        ;

});
