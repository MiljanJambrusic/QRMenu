require('./bootstrap');
require('alpinejs');
require('jquery');
import $ from 'jquery';
window.$ = window.jQuery = $;
require('jQuery.print')


$("body").on("click","#btn-for-print",function (){
    $(".div-for-print").print();
});


