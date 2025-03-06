
@php
$str =  explode("\\",$row->medicineBill->model_type)[2];
$str= preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $str);
if (str_contains($str, 'Ipd')){
    $str = str_replace('Ipd','IPD',$str );
}
if (str_contains($str, 'Opd')){
    $str = str_replace('Opd','OPD',$str );
}
@endphp
{{ $str }}
