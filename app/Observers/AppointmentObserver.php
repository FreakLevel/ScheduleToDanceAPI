<?php

namespace App\Observers;

use App\Appointment;

class AppointmentObserver
{
    /**
     * Handle the appointment "creating" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function creating(Appointment $appointment)
    {
      $schedule = $appointment->schedule;
      $date = new DateTime($schedule->format('Y-m-d H:i'));
      $today = new DateTime(date('Y-m-d H:i', time()));
      if ($date->diff($today)->format('%r') != '') {
        return true
      } else {
        return false
      }
    }

    /**
     * Handle the appointment "updating" event.
     *
     * @param  \App\Appointment  $appointment
     * @return void
     */
    public function updating(Appointment $appointment)
    { 
      $schedule = $appointment->schedule;
      $date = new DateTime($schedule->format('Y-m-d H:i'));
      $today = new DateTime(date('Y-m-d H:i', time()));
      if ($date->diff($today)->format('%r') != '') {
        return true
      } else {
        return false
      }
    }
}
