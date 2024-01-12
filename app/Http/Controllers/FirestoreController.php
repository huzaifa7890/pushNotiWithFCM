<?php
// App\Http\Controllers\FirestoreController.php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\FirestoreDataUpdateJob;

class FirestoreController extends Controller
{
    public function displayStudents()
    {
        // Fetch data from Firestore and store it in the database
        Artisan::call('firestore:update');

        // Fetch data from the database
        $students = Student::all();

        return view('display', ['students' => $students]);
    }

    public function displayFirestoreData()
    {
        // Fetch Firestore data using the controller
        // (You can implement the logic directly in the controller)
        $studentsCollection = app('firebase.firestore')->database()->collection('students');
        $documents = $studentsCollection->documents();

        $firestoreData = [];

        foreach ($documents as $document) {
            $firestoreData[] = $document->data();
        }

        return view('firestore', ['firestoreData' => $firestoreData]);
    }
    public function updateFirestoreData()
    {
        // Implement your logic to fetch data from Firestore
        // For example:
        $studentsCollection = app('firebase.firestore')->database()->collection('students');
        $documents = $studentsCollection->documents();

        foreach ($documents as $document) {
            $data = $document->data();

            // Insert or update each student record in the database
            Student::updateOrCreate(
                ['age' => $data['age']],
                [
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'age' => $data['age'],
                    // Add other fields as needed
                ]
            );
        }
    }
}
