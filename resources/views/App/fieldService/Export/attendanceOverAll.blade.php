
<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center;font-weight: bold;font-size: 19px">Attendance Over All Report</th>
        </tr>
        <tr>
            <th style="font-weight: bold">Employee</th>
            <th style="font-weight: bold">Campaign Name</th>
            <th style="font-weight: bold">CheckIn Location</th>
            <th style="font-weight: bold">Status</th>
            <th style="font-weight: bold">Check In Date</th>
            <th style="font-weight: bold">Check Out Date</th>
            <th style="font-weight: bold">Attendance Photo</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600 fs-6 fw-semibold" id="allSaleTable">

        @foreach ($datas as $attendanceRecord)
        <tr class="">
            <td>{{$attendanceRecord['fn']}} {{$attendanceRecord['ln']}}</td>
            <td class="text-start">{{$attendanceRecord['campaign']}}</td>
            <td>{{$attendanceRecord['location_name']}}</td>
            <td>
                @if($attendanceRecord->status=='checkIn')
                <span class="text-success">{{$attendanceRecord->status}}</span>
                @else
                <span class="text-danger">{{$attendanceRecord->status}}</span>
                @endif
            </td>
            <td class="text-start">{{fdate($attendanceRecord->checkin_datetime,false)}}</td>
            <td>{{fdate($attendanceRecord->checkout_datetime,false)}}</td>
            <td>
                @php
                $photo = json_decode($attendanceRecord->photo);
                $src = asset('/storage/checkIn/'.$photo->checkIn);

                @endphp
                @if ($photo)
                    <a href="{{$src}}">{{$src}}</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
