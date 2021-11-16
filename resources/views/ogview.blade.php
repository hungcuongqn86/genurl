<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:url" content="{{ $sLink->Url->original }}" />
    <meta property="og:title" content="{{ $sLink->Url->title }}" />
    <meta property="og:image" content="{{ URL::asset('images/') }}/{{ $sLink->Url->image }}" />
    <meta property="og:description" content="{{ $sLink->Url->description }}" />
    <title>{{ $sLink->Url->title }}</title>
</head>
<body>
    <h1></h1>
</body>
</html>
