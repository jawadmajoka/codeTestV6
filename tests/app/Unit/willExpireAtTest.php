<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\app\Helpers\TeHelper;
use Log;

class willExpireAtTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function willExpireAt()
    {

        $currentTime = Carbon\Carbon::now();
        $created_at = $currentTime->toDateTimeString();
        $due_time = $currentTime->addDays(30)->format('Y-m-d H:i:s');

        $teHelper = new TeHelper();
        $teHelper->willExpireAt($due_time, $created_at);
        $response = $teHelper->willExpireAt($due_time, $created_at);
        Log::info("------------------- Expire at time is $response -----------------------");
    }
}
