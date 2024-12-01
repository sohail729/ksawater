<?php

use App\Models\CarBrand;
use Illuminate\Support\Facades\Storage;

if(!function_exists('uploadFileToS3')){
    function uploadFileToS3($image, $folder){
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        Storage::disk('s3')->put($folder.'/'. $imageName, file_get_contents($image), 'public');
        return Storage::disk('s3')->url($folder.'/'. $imageName);
    }
}

if(!function_exists('carBrands')){
    function carBrands(){
        return CarBrand::orderBy('name')->get();
    }
}

if(!function_exists('generateUniqueOrderNumber')){
    function generateUniqueOrderNumber() {
        // Generate 6 bytes of random data and convert to hexadecimal
        $randomBytes = strtoupper(bin2hex(random_bytes(6)));
        // Split into two 6-character groups separated by a dash
        return substr($randomBytes, 0, 6) . '-' . substr($randomBytes, 6, 6);
    }
}

if(!function_exists('num2word')){
function num2word($n){if(empty($n))return null;$w=[1=>'One',2=>'Two',3=>'Three',4=>'Four',5=>'Five',6=>'Six',7=>'Seven',8=>'Eight',9=>'Nine'];return($n>=1&&$n<=9)?$w[$n]:null;}
}

