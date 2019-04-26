@extends('backend.report.layouts.master', ['headerData' => $headerData,'printIt' => 1])
@section('extraStyle')
    <style>
        @page {
            size:  A4 landscape;
        }
    </style>
@endsection
@section('reportBody')
    <div class="report-body">
        <div class="report-data">
            <div class="row">
                <div class="col-xs-12">
                    <table class="classic">
                        <thead>
                        <tr>
                            <th width="5%" rowspan="2">SL</th>
                            <th width="10%" rowspan="2">NAME</th>
                            <th width="55%" colspan="{{count($monthDates)}}">Day of Month</th>
                            <th width="5%" rowspan="2">Present</th>
                            <th width="5%" rowspan="2">Absent</th>
                            <th width="5%" rowspan="2">ToT.DAYS</th>
                        </tr>
                        <tr>
                            @foreach($monthDates as $date => $value)
                            <th @if($value['weekend']) class="weekend" @endif>{{$value['day']}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$employee->name}}</td>
                                    @php
                                        $tPresent = 0;
                                        $tabsent = 0;

                                    @endphp
                                    @foreach($monthDates as $date => $value)
                                        @php
                                            $status = '';
                                            $color = '';

                                                if(isset($attendanceData[$employee->id][$date])) {
                                                    if($attendanceData[$employee->id][$date]['present']  == 1){
                                                        $status = 'P';
                                                        $color = 'green';
                                                        $tPresent++;
                                                        if($value['weekend'] || isset($calendarData[$date])){
                                                            $tPresent--;
                                                        }

                                                    }
                                                    else{
                                                        if(!isset($employeesWorkoutside[$employee->id][$date])
                                                        && !isset($employeesLeaves[$employee->id][$date])
                                                        && !isset($calendarData[$date])
                                                        && !$value['weekend']
                                                        ){
                                                            $status = 'A';
                                                            $tabsent++;
                                                            $color = 'red';
                                                        }

                                                    }
                                                }


                                                 if(isset($calendarData[$date])) {
                                                        $status .= $calendarData[$date];
                                                        $color = 'holiday';
                                                        $tPresent++;
                                                 }


                                                if($value['weekend']){
                                                        $status .= 'W';
                                                        $color = 'weekend';
                                                        $tPresent++;
                                                }
                                                if(isset($employeesLeaves[$employee->id][$date])) {
                                                        $status = 'L';
                                                        $color = 'blue';
                                                        $tPresent++;
                                                }
                                                if(isset($employeesWorkoutside[$employee->id][$date])) {
                                                        $status = 'WO';
                                                        $color = 'green';
                                                        $tPresent++;
                                                }
                                        @endphp
                                        <td class="{{$color}}">{{$status}}</td>
                                    @endforeach
                                    <td>
                                        {{($tPresent)}}
                                    </td>
                                    <td>
                                        {{$tabsent}}
                                    </td>
                                    <td>
                                        {{$tPresent + $tabsent}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="report-authority">
            <div class="row">
                <div class="col-xs-4">
                    <h5>Printed By: {{auth()->user()->name}}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection