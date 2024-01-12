<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
</head>
<body>
    <h1>Student List</h1>
    <ul>
        @foreach($students as $student)
            <li>
                First Name: {{ $student['firstname'] }}<br>
                Last Name: {{ $student['lastname'] }}<br>
                Age: {{ $student['age'] }}
            </li>
        @endforeach
    </ul>
</body>
</html>
