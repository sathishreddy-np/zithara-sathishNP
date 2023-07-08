## Documentation: Dispatching Jobs for Users

This documentation provides an overview and usage instructions for dispatching jobs for users using the provided code snippets and running the necessary commands in the terminal.

### Step 1: Define Route

Add the following route definition to your Laravel routes file (`web.php`):

```php
Route::post('/users/{user_id}', [JobController::class, 'dispatchJobs']);
```

This route will listen for POST requests to `/users/{user_id}` and execute the `dispatchJobs` method in the `JobController` class.

### Step 2: Implement the Job Controller

Create a file called `JobController.php` in the `app/Http/Controllers` directory (if not already created). Copy and paste the following code into the file:

```php
<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUserJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function dispatchJobs(Request $request)
    {
        $user_id = $request->user_id;

        $queue_name = 'user_' . $user_id;
        dispatch((new ProcessUserJob())->onQueue($queue_name));

        return "Jobs dispatched for user: $user_id";
    }
}
```

The `dispatchJobs` method receives a `Request` object and extracts the `user_id` from it. It then creates a queue name based on the user ID and dispatches a `ProcessUserJob` instance to the specified queue. Finally, it returns a string indicating that the jobs have been dispatched for the given user.

### Step 3: Implement the Process User Job

Create a file called `ProcessUserJob.php` in the `app/Jobs` directory (if not already created). Copy and paste the following code into the file:

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        for ($i = 1; $i <= 10000; $i++) {
            echo "Processing user job: $i\n";
        }
    }
}
```

The `ProcessUserJob` class represents the job that will be executed in the background. In this example, it contains a `handle` method that processes the job. In this case, it simulates processing by printing a message for each iteration of a loop.

### Step 4: Run the Queue Workers

To start processing the dispatched jobs, open a terminal and navigate to the root directory of your Laravel project. Run the following commands to start three queue workers, each listening to a specific queue:

```bash
php artisan queue:work --queue=user_1 &
php artisan queue:work --queue=user_2 &
php artisan queue:work --queue=user_3
```

Each command starts a separate worker process that listens to the specified queue. Make sure to adjust the queue names (`user_1`, `user_2`, and `user_3`) according to your requirements.

The ampersand (`&`) at the end of each command allows the workers to run in the background, so you can continue using the terminal.

### Step 5: Test the Dispatching of Jobs

To test the dispatching of jobs, make a POST request to one of the following URLs, passing the user ID as a parameter:

- http://your-app-url/users/1
- http://your-app-url/users/2
- http://your-app-url/users/3

Replace `your-app-url` with the actual URL of your Laravel application.

After making the request, you should see the message "Jobs dispatched for user: [user_id]" indicating that the jobs have been dispatched for the specified user.

