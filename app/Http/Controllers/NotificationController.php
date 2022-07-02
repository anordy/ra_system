<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        return view('notifications');
    }
}
