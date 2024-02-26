<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<style>
    html,
    body {
        background-color: #fff;
        height: 100vh;
        margin: 0;
    }

    .wrappper {
        width: 100%;
        height: 100%;
    }

    .google-map {
        width: 100%;
        height: 100%;
    }

    #app {
        height: 100%;
    }



    @import url("https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i");

    .image-marker {
        width: 50px;
        height: 50px;
        user-select: none;
        border: 0px none;
        padding: 0px;
        margin: 0px;
        max-width: none;
    }

    .border-marker {
        cursor: pointer;
        border-radius: 7px;
        background: linear-gradient(360deg, #C8EF62 0%, #268597 50.52%, #116272 100%);
        padding: 5px;
        width: 50px;
        position: relative;
        height: 50px;
        box-shadow: 0 6px 9px rgba(0, 21, 41, 0.4);
    }



    .loc-visit:after {
        content: "";
        position: absolute;
        right: -8px;
        top: -7px;
        background-image: url("/images/ok.svg");
        width: 22px;
        height: 22px;
        background-repeat: no-repeat;
        background-size: 22px;
    }

    .pic-overlay {
        height: fit-content;
        vertical-align: top;
        margin-right: 3px;
        min-height: 82px;
        min-width: 82px;
        border-radius: 3px;
        overflow: hidden;
    }

    .pic-overlay img {
        vertical-align: top;
        height: 82px;
        width: 82px;
    }

    .address-modal {
        position: absolute;
        top: -42px;
        left: 5px;
        transform: translate(-50%, -100%);
        max-width: 280px;
        width: 100%;
        padding: 10px 10px 20px;
        background: #1E1E1E;
        border-radius: 5px;
        color: #FFF;
        font-family: Roboto;
        font-size: 10px;
        line-height: 12px;
    }

    .address-modal__btn {
        position: absolute;
        right: 5px;
        top: 5px;
        width: 24px;
        height: 24px;
        background: #151515 url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMuMTY3MzcgMy4xNjczN0MzLjM5MDUzIDIuOTQ0MjEgMy43NTIzMyAyLjk0NDIxIDMuOTc1NDkgMy4xNjczN0w3IDYuMTkxODlMMTAuMDI0NSAzLjE2NzM3QzEwLjI0NzcgMi45NDQyMSAxMC42MDk1IDIuOTQ0MjEgMTAuODMyNiAzLjE2NzM3QzExLjA1NTggMy4zOTA1MyAxMS4wNTU4IDMuNzUyMzMgMTAuODMyNiAzLjk3NTQ5TDcuODA4MTEgN0wxMC44MzI2IDEwLjAyNDVDMTEuMDU1OCAxMC4yNDc3IDExLjA1NTggMTAuNjA5NSAxMC44MzI2IDEwLjgzMjZDMTAuNjA5NSAxMS4wNTU4IDEwLjI0NzcgMTEuMDU1OCAxMC4wMjQ1IDEwLjgzMjZMNyA3LjgwODExTDMuOTc1NDkgMTAuODMyNkMzLjc1MjMzIDExLjA1NTggMy4zOTA1MyAxMS4wNTU4IDMuMTY3MzcgMTAuODMyNkMyLjk0NDIxIDEwLjYwOTUgMi45NDQyMSAxMC4yNDc3IDMuMTY3MzcgMTAuMDI0NUw2LjE5MTg5IDdMMy4xNjczNyAzLjk3NTQ5QzIuOTQ0MjEgMy43NTIzMyAyLjk0NDIxIDMuMzkwNTMgMy4xNjczNyAzLjE2NzM3WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==") 50% 50% no-repeat;
        background-size: 16px;
        cursor: pointer;
        border-radius: 50%;
    }

    .address-modal:after {
        content: "";
        position: absolute;
        left: 48%;
        top: 100%;
        width: 19px;
        height: 19px;
        margin-top: -10px;
        margin-left: -5px;
        background-color: inherit;
        border-left: none;
        border-top: none;
        transform: rotate(45deg);
        z-index: -1;
    }

    .address-info {
        display: flex;
    }


    .address-brief {
        word-break: break-word;
    }

    .address-brief__title {
        padding-right: 24px;
        margin-bottom: 5px;
        font-size: 18px;
        line-height: 21px;
    }

    .address-brief__item {
        position: relative;
        padding-left: 18px;
    }

    .address-brief__item:before {
        position: absolute;
        content: "";
        top: 0;
        left: 0;
        width: 12px;
        height: 12px;
        display: block;
        background: transparent 50% 50% no-repeat;
        background-size: cover;
    }

    .address-brief__item--stars {
        margin-bottom: 9px;
    }

    .address-brief__item--stars:before {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTIiIHZpZXdCb3g9IjAgMCAxMiAxMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYuMDAwMDIgMS4yNUM2LjE4OTQyIDEuMjUgNi4zNjI1MiAxLjM1NyA2LjQ0NzIyIDEuNTI2MzlMNy43MzY2NyA0LjEwNTNMMTAuNTcyIDQuNTE3N0MxMC43NjAzIDQuNTQ1MSAxMC45MTY4IDQuNjc3MDEgMTAuOTc1NiA0Ljg1OEMxMS4wMzQ0IDUuMDM5IDEwLjk4NTMgNS4yMzc2NSAxMC44NDkxIDUuMzcwNUw4Ljc4NTUyIDcuMzgyNDVMOS4yNDk3MiAxMC4yMTkyQzkuMjgwMzcgMTAuNDA2NyA5LjIwMjE3IDEwLjU5NTQgOS4wNDc4MiAxMC43MDYyQzguODkzNTIgMTAuODE2OSA4LjY4OTcyIDEwLjgzMDcgOC41MjE5MiAxMC43NDE3TDYuMDAwMDIgOS40MDM1NUwzLjQ3ODEyIDEwLjc0MTdDMy4zMTAzMSAxMC44MzA3IDMuMTA2NTMgMTAuODE2OSAyLjk1MjIgMTAuNzA2MkMyLjc5Nzg3IDEwLjU5NTQgMi43MTk2NSAxMC40MDY3IDIuNzUwMzMgMTAuMjE5MkwzLjIxNDUzIDcuMzgyNDVMMS4xNTA5NyA1LjM3MDVDMS4wMTQ3MSA1LjIzNzY1IDAuOTY1NjgxIDUuMDM5IDEuMDI0NDkgNC44NThDMS4wODMyOSA0LjY3NzAxIDEuMjM5NzMgNC41NDUxIDEuNDI4MDUgNC41MTc3TDQuMjYzMzUgNC4xMDUzTDUuNTUyODIgMS41MjYzOUM1LjYzNzUyIDEuMzU3IDUuODEwNjIgMS4yNSA2LjAwMDAyIDEuMjVaTTYuMDAwMDIgMi44NjgwNEw1LjA0MDk3IDQuNzg2MTFDNC45Njc4MSA0LjkzMjQ2IDQuODI3NjYgNS4wMzM3NSA0LjY2NTc0IDUuMDU3M0wyLjU3NDIxIDUuMzYxNUw0LjA5OTA3IDYuODQ4MjVDNC4yMTU5MSA2Ljk2MjE1IDQuMjY5ODEgNy4xMjU5NSA0LjI0MzQ1IDcuMjg3TDMuOTAwMDIgOS4zODU3NUw1Ljc2NTY3IDguMzk1ODVDNS45MTIyMiA4LjMxODA1IDYuMDg3ODIgOC4zMTgwNSA2LjIzNDM3IDguMzk1ODVMOC4xMDAwMiA5LjM4NTc1TDcuNzU2NTcgNy4yODdDNy43MzAyMiA3LjEyNTk1IDcuNzg0MTIgNi45NjIxNSA3LjkwMDk3IDYuODQ4MjVMOS40MjU4MiA1LjM2MTVMNy4zMzQzMiA1LjA1NzNDNy4xNzIzNyA1LjAzMzc1IDcuMDMyMjIgNC45MzI0NiA2Ljk1OTA3IDQuNzg2MTFMNi4wMDAwMiAyLjg2ODA0WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==");
    }

    .address-brief__item--address:before {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTIiIHZpZXdCb3g9IjAgMCAxMiAxMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYgNUM2LjU1MjI4IDUgNyA0LjU1MjI4IDcgNEM3IDMuNDQ3NzIgNi41NTIyOCAzIDYgM0M1LjQ0NzcyIDMgNSAzLjQ0NzcyIDUgNEM1IDQuNTUyMjggNS40NDc3MiA1IDYgNVoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik02IDExQzMuNTkgMTEgMSAxMC4zNzUgMSA4Ljk5OTk5QzEgOC41MjQ5OSAxLjMyNSA3Ljg1OTk5IDIuODY1IDcuMzk5OTlMMy4xNSA4LjM1OTk5QzIuMiA4LjYzOTk5IDIgOC45NDk5OSAyIDguOTk5OTlDMiA5LjI1NDk5IDMuMzc1IDkuOTk5OTkgNiA5Ljk5OTk5QzguNjI1IDkuOTk5OTkgMTAgOS4yNTQ5OSAxMCA4Ljk5OTk5QzEwIDguOTQ5OTkgOS44IDguNjM5OTkgOC44NTUgOC4zNTk5OUw5LjE0IDcuMzk5OTlDMTAuNjc1IDcuODU5OTkgMTEgOC41MjQ5OSAxMSA4Ljk5OTk5QzExIDEwLjM3NSA4LjQxIDExIDYgMTFaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNNiA5LjIwNUw1LjY0NSA4Ljg1QzUuNTQgOC43NDUgMyA2LjE4IDMgNEMzIDIuMzQ1IDQuMzQ1IDEgNiAxQzcuNjU1IDEgOSAyLjM0NSA5IDRDOSA2LjE4IDYuNDYgOC43NDUgNi4zNTUgOC44NTVMNiA5LjIwNVpNNiAyQzQuODk1IDIgNCAyLjg5NSA0IDRDNCA1LjI1IDUuMjMgNi45IDYgNy43N0M2Ljc3IDYuOSA4IDUuMjUgOCA0QzggMi44OTUgNy4xMDUgMiA2IDJaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K");
    }

    .address-brief__item--time:before {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTIiIHZpZXdCb3g9IjAgMCAxMiAxMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTYgMkMzLjc5MDg2IDIgMiAzLjc5MDg2IDIgNkMyIDguMjA5MTUgMy43OTA4NiAxMCA2IDEwQzguMjA5MTUgMTAgMTAgOC4yMDkxNSAxMCA2QzEwIDMuNzkwODYgOC4yMDkxNSAyIDYgMlpNMSA2QzEgMy4yMzg1NyAzLjIzODU3IDEgNiAxQzguNzYxNCAxIDExIDMuMjM4NTcgMTEgNkMxMSA4Ljc2MTQgOC43NjE0IDExIDYgMTFDMy4yMzg1NyAxMSAxIDguNzYxNCAxIDZaTTYgM0M2LjI3NjE1IDMgNi41IDMuMjIzODYgNi41IDMuNVY1Ljc5MjlMNy44NTM1NSA3LjE0NjQ1QzguMDQ4OCA3LjM0MTcgOC4wNDg4IDcuNjU4MyA3Ljg1MzU1IDcuODUzNTVDNy42NTgzIDguMDQ4OCA3LjM0MTcgOC4wNDg4IDcuMTQ2NDUgNy44NTM1NUw1LjY0NjQ1IDYuMzUzNTVDNS41NTI3IDYuMjU5OCA1LjUgNi4xMzI2IDUuNSA2VjMuNUM1LjUgMy4yMjM4NiA1LjcyMzg1IDMgNiAzWiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==");
    }

    .address-brief__item--schedule:before {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTIiIHZpZXdCb3g9IjAgMCAxMiAxMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQuNSAxQzQuNzc2MTQgMSA1IDEuMjIzODYgNSAxLjVWMkg3VjEuNUM3IDEuMjIzODYgNy4yMjM4NSAxIDcuNSAxQzcuNzc2MTUgMSA4IDEuMjIzODYgOCAxLjVWMkg5LjVDMTAuMDUyMyAyIDEwLjUgMi40NDc3MiAxMC41IDNWOS41QzEwLjUgMTAuMDUyMyAxMC4wNTIzIDEwLjUgOS41IDEwLjVIMi41QzEuOTQ3NzEgMTAuNSAxLjUgMTAuMDUyMyAxLjUgOS41VjNDMS41IDIuNDQ3NzIgMS45NDc3MSAyIDIuNSAySDRWMS41QzQgMS4yMjM4NiA0LjIyMzg2IDEgNC41IDFaTTQgM0gyLjVWNC41SDkuNVYzSDhWMy41QzggMy43NzYxNCA3Ljc3NjE1IDQgNy41IDRDNy4yMjM4NSA0IDcgMy43NzYxNCA3IDMuNVYzSDVWMy41QzUgMy43NzYxNCA0Ljc3NjE0IDQgNC41IDRDNC4yMjM4NiA0IDQgMy43NzYxNCA0IDMuNVYzWk05LjUgNS41SDIuNVY5LjVIOS41VjUuNVoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=");
    }

    .address-brief__item:not(.address-brief__item--stars) {
        margin-bottom: 5px;
        color: rgba(255, 255, 255, 0.7);
    }

    .address-achievements {
        margin-top: 12px;
    }

    .address-achievements__header {
        margin-bottom: 10px;
        font-size: 13px;
        line-height: 15px;
    }

    .address-achievements__pics {
        display: flex;
    }

    .address-achievements__pic:not(:last-of-type) {
        margin-right: 10px;
    }

    h2,
    h3 {
        margin: 0;
    }

    button {
        border: none;
    }



    .popup-bubble {
        position: absolute;
        top: 0;
        left: 0;
        transform: translate(-50%, -100%);
        background-color: white;
        padding: 5px;
        border-radius: 5px;
        font-family: sans-serif;
        overflow-y: auto;
        max-height: 60px;
        box-shadow: 0px 2px 10px 1px rgba(0, 0, 0, 0.5);
    }

    .popup-bubble-anchor {
        position: absolute;
        width: 100%;
        bottom: 8px;
        left: 0;
    }

    /* .popup-bubble-anchor::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        transform: translate(-50%, 0);
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 8px solid white;
    } */

    .popup-container {
        cursor: auto;
        height: 0;
        position: absolute;
        max-width: 220px;
        width: 100%;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- Scripts -->
    <!-- google map-->
    <script type="text/javascript">
        var params = new URLSearchParams(window.location.search.substring(1));
        var lang = 'en'
        if (params.get("lang")) {
            lang = params.get("lang")
        }
        var scriptTag = document.createElement('script');
        var scriptSrc = '//maps.googleapis.com/maps/api/js?libraries=drawing,places&key=YOUR_KEY_HERE';

        scriptSrc = '//maps.googleapis.com/maps/api/js?language=' + lang + '&libraries=places&key=AIzaSyBdjLm64slqYH4freRjVVRp02oI6hLvi8s';

        scriptTag.setAttribute('src', scriptSrc);
        scriptTag.setAttribute('async', '');
        document.head.appendChild(scriptTag);
    </script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdjLm64slqYH4freRjVVRp02oI6hLvi8s&libraries=places">
    </script> -->
    <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"> </script>
    <!-- <link rel="stylesheet" href="{{ asset('css/map/index.css') }}"> -->
</head>
<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d577325.3481417937!2d36.825102872473444!3d55.58152436219907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d4b0c9%3A0x3d44d6cc5757cf4c!2z0JzQvtGB0LrQstCw!5e0!3m2!1sru!2sru!4v1617828427687!5m2!1sru!2sru" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->

<body>

    <!-- Vue.js тэг -->

    @yield('content')



    <script src="{{ mix('map/js/app.js') }}"></script>

</body>

</html>