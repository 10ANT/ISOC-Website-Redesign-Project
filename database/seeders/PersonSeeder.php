<?php

namespace Database\Seeders;

use App\Enums\PersonGroup;
use App\Models\Person;
use Illuminate\Database\Seeder;

/**
 * Leadership and founding members taken from the current isocjm.org site.
 * Positions should be confirmed with ISOC Jamaica before launch.
 */
class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $board = [
            ['Fitzroy Thompson', 'Chairman'],
            ['Gary Campbell', 'Vice Chairman'],
            ['Otis Osbourne', 'Board Advisory Council'],
            ['Lyndel McDonald', 'Board Voting Delegate'],
            ['Andrea Livingston-Prince', 'Board Member'],
            ['Jesica Campbell', 'Board Secretary'],
        ];

        $officers = [
            ['Natalie Rose', 'President'],
            ['Neil Williams', 'Executive Vice President'],
            ['Jodiann Allen', 'Administrative Vice President'],
            ['Winsome Bennett', 'Secretary'],
        ];

        $founding = [
            ['JodiAnn Allen', 'End User'], ['Patrick Anglin', 'Academia'], ['Elton Austin', 'Technical Community'],
            ['Gary Campbell', 'Public Sector'], ['Jesica Campbell', 'Student, End User'],
            ['Bryanna Chang', 'Student, Technical Community'], ['Roger Chang', 'Civil Society'],
            ['Sean Finn', 'Student, Public Sector'], ['Stephen Gentles', null], ['Neil Gittens', null],
            ['Adriel Grant', null], ['Jason Hynds', 'Civil Society, Technical Community'], ['Duanne Johnson', null],
            ['Robert Lancashire', null], ['Andrea Livingston-Prince', null], ['Damion Malcolm', null],
            ['Lyndel McDonald', 'Private Sector'], ['Jermaine McDonald', null], ['Maurice Miller', null],
            ['Nashiem Morgan', null], ['Maurice Morris', null], ['Otis Osbourne', null], ['Davian Richards', null],
            ['Natalie Rose', null], ['Carlton Samuels', null], ['Fitzroy Thompson', null],
            ['Adrian Vanhorne', null], ['Neil Williams', null],
        ];

        foreach ($board as $i => [$name, $position]) {
            Person::updateOrCreate(['name' => $name, 'group' => PersonGroup::Board], ['position' => $position, 'sort_order' => $i + 1]);
        }

        foreach ($officers as $i => [$name, $position]) {
            Person::updateOrCreate(['name' => $name, 'group' => PersonGroup::Officer], ['position' => $position, 'sort_order' => $i + 1]);
        }

        foreach ($founding as [$name, $sector]) {
            Person::updateOrCreate(['name' => $name, 'group' => PersonGroup::Founding], ['sector' => $sector]);
        }
    }
}
