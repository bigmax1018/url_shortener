@extends('layouts.app')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <style>
        .addUrlInput{
            height: 49px;
            border-radius: 0px;
            margin-bottom: 40px !important;
        }
        

        button#button-addon2 {
            margin-bottom: 50px;
            background-color: #333;
            color: #fff;
            position: relative;

        }

        button#button-addon2:hover {
            border: solid 1px #dad5d5;
            background-color: #f7f7f7;
            color: #333;
            position: relative;
            transition: background-color 0.5s;
        }

        input.form-control:focus {
            border: none !important;
            outline: none !important;
        }

        .shortner {
            padding-top: 50px;
        }

        .copyLink {
            padding: 35px;
            height: 100px;
            background-color: #d7eeff;
            font-size: 24px;
            display: none;
        }

        span#output_url {
            padding-right: 120px;
        }

        span#clipBoard {
            cursor: pointer;
        }

        span#clipBoard:hover {
            transition: color 0.5s ease;
            color: #12a7f3;
        }
        .wrapper_url_shortener{
            padding-top: 150px;
        }

    </style>
@endsection

@section('content')
    <div id="app" class="wrapper_url_shortener">
        <url-shortener authorized-user="{{auth()->user()->id ?? null}}"></url-shortener>
    </div>
@endsection

