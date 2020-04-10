<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('diff', function () {

    $arr1 =[1,2,3,4];
    $arr2 =[1];

    return array_diff($arr1, $arr2);
});

Route::get('the_same', function () {
    $arr1 =[1,2,3,4];
    $arr2 =[1,4,6,8];
    return the_same($arr1, $arr2);
});

function the_same($arr1, $arr2){
    $c1 = count($arr1);
    $c2 = count($arr2);
    $n1 = $n2 = 0;
    $arr3 = [];
    while ($n1<$c1 && $n2 < $c2){
        if ($arr1[$n1] > $arr2[$n2]){
            $n1++;
        }else if($arr1[$n1] < $arr2[$n2]){
            $n2++;
        }else{
            $n1++;
            $n2++;

            $arr3[] = $arr1[$n1];
        }
    }

    return $arr3;
}


Route::get('rand_arr', function () {
    $arr1 =[1,2,3,4,5,6,7,8];
    return rand_arr($arr1);
});

function rand_arr($arr1){
    $c1 = count($arr1)-1;
    while($c1 > 0){
        $r = rand(0, $c1);
        if ($r !== $c1){
            $tmp = $arr1[$r];
            $arr1[$r] = $arr1[$c1];
            $arr1[$c1] = $tmp;
        }
        $c1--;

    }
    return $arr1;
}

Route::get('number_alphabet', function () {
    $str = '1sd32dsa2dfds21sad3we';
    return number_alphabets($str);
});
function number_alphabets($str){
    $num_str = preg_split('/[a-zA-Z]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    $letter_str = preg_split('/\d/', $str, -1, PREG_SPLIT_NO_EMPTY);
    $new_arr = [];
    for ($i=0; $i<count($num_str); $i++){
        $new_arr[] = $num_str[$i].':'.$letter_str[$i];
    }
    return $new_arr;
}

//5.求n以内的质数（质数的定义：在大于1的自然数中，除了1和它本身意外，无法被其他自然数整除的数）#
Route::get('prime_number', function () {
    $n = 99;
    return prime_number($n);
});

function prime_number($n){
    $prime_arr = [];
    for ($i=2; $i<=99; $i++){
        if ($i%2 !== 0){
            $prime_arr[] = $i;
        }
    }
    
    return $prime_arr;
}


//6.约瑟夫环问题#
//相关题目：一群猴子排成一圈，按1,2,…,n依次编号。然后从第1只开始数，
//数到第m只,把它踢出圈，从它后面再开始数， 再数到第m只，在把它踢出去…，如此不停的进行下去， 
//直到最后只剩下一只猴子为止，那只猴子就叫做大王。要求编程模拟此过程，输入m、n, 输出最后那个大王的编号。
Route::get('monkey', function () {
    $n = 99;
    $m = 15;
    return monkey($n, $m);
});

function monkey($n, $m){
    $tmp_arr = [];
    $tmp_num = 0;
    $arr = range(1, $n);
    
    // 存在
    $i = 0;
    while (count($arr) > 1){
        $i++;
        $tmp = array_shift($arr);
        if ($i % $m != 0){
            array_push($arr, $tmp);
        }
    }
    
    return $arr;
}

//function get_king_mokey($n, $m)
//{
//    $arr = range(1, $n);
//
//    $i = 0;
//
//    while (count($arr) > 1) {
//        $i++;
//        $survice = array_shift($arr);
//
//        if ($i % $m != 0) {
//            array_push($arr, $survice);
//        }
//    }
//
//    return $arr[0];
//}
//

//如何快速寻找一个数组里最小的1000个数#
//思路：假设最前面的1000个数为最小的，算出这1000个数中最大的数，
//然后和第1001个数比较，如果这最大的数比这第1001个数小的话跳过，
//如果要比这第1001个数大则将两个数交换位置，并算出新的1000个数里面的最大数，再和下一个数比较，以此类推。
//寻找最小的k个数
//题目描述
//输入n个整数，输出其中最小的k个。
/**
 * 获取最小的k个数
 * @param  array $arr
 * @param  int $k   [description]
 * @return array
 */
Route::get('sorts', function () {
    $n = range(10,20);
    return sorts(rand_arr($n));
});

function sorts($n){
    
}


Route::get('first_order', function () {
    $n = rand_arr(range(10,20));
    return quick_sort($n);
});


// 快速排序
// 选一个数对比所有数
function first_order($arr){
    $num = 0;
    for ($i=0; $i < count($arr); $i++){ // 第一排
        for ($j=0; $j < count($arr); $j++){ // 第二排
            $num++;
            if ($arr[$i] > $arr[$j]){// 大于就排后面
                $tmp = $arr[$i];
                $arr[$i] = $arr[$j];
                $arr[$j] = $tmp;
            }
        }
    }
    
    dump($num);
    return $arr;
}


function quick_sort($data)
{
    $num = 0;
    $size = count($data);
    if($size>1) {
        $key = $data[0];
        $l = $r = [];
        for ($i = 1; $i < $size; $i++) {
            $num++;
            if ($data[$i] >= $key)
                $r[] = $data[$i];
            else
                $l[] = $data[$i];
        }
        $l_re = quick_sort($l);
        $r_re = quick_sort($r);

        return array_merge($l_re, [$key], $r_re);
    }
    
    dump($num);
    return $data;
}

//1.编写一个PHP函数，求任意n个正负整数里面最大的连续和，要求算法时间复杂度尽可能低。
Route::get('/test', 'Weixin\GoodsController@getGoodsClass');
