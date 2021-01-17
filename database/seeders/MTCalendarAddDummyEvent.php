<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MTfullcalendar;

class MTCalendarAddDummyEvent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = [
      	['summary'=>'Demo Event-1', 'start'=>'2021-01-15', 'end'=>'2021-01-20'],
      	['summary'=>'Demo Event-2', 'start'=>'2021-01-15', 'end'=>'2021-01-21'],
      	['summary'=>'Demo Event-3', 'start'=>'2021-01-15', 'end'=>'2021-01-19'],
      	['summary'=>'Demo Event-8', 'start'=>'2021-01-11', 'end'=>'2021-01-19'],
        ['summary'=>'Demo Event-4', 'start'=>'2021-01-10', 'end'=>'2021-01-11'],
        ['summary'=>'Demo Event-5', 'start'=>'2021-01-09', 'end'=>'2021-01-10'],
        ['summary'=>'Demo Event-6', 'start'=>'2021-01-08', 'end'=>'2021-01-09'],
        ['summary'=>'Demo Event-7', 'start'=>'2021-01-07', 'end'=>'2021-01-08'],
        ];
        foreach ($data as $key => $value) {
        	MTfullcalendar::create($value);
        }
    }
}
