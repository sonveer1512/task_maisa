@php
$sess_data = session('user');
@endphp

Name - {{ $sess_data['name'] }} <br>
Email - {{ $sess_data['email'] }} <br>

<a href="{{ url('logout') }}" class="registerbtn">Logout</a>